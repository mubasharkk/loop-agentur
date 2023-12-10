<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Services\PaymentService;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery\MockInterface;
use Tests\TestCase;

class TestOrdersApiEndpoints extends TestCase
{

    use DatabaseMigrations, DatabaseTransactions;

    private $customers;

    private $products;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customers = Customer::factory()->count(3)->create();
        $this->products = Product::factory()->count(30)->create();
    }

    public function test_create_order()
    {
        $customer = $this->customers->random(1)->first();
        $product = $this->products->random(1)->first();

        $response = $this->post('/api/orders', [
            'customer_id' => $customer->id,
            'product_id'  => $product->id,
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            'data' => [
                'customer' => ['id' => $customer->id],
                'products' => [['id' => $product->id]],
                'value'    => $product->price,
                'payed'    => false,
            ],
        ]);

        $order = \json_decode($response->content())->data;

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'id'          => $order->id,
        ]);
    }

    public function test_add_products_to_order()
    {
        $customer = $this->customers->random(1)->first();
        $product = $this->products->random(1)->first();

        $response = $this->post('/api/orders', [
            'customer_id' => $customer->id,
            'product_id'  => $product->id,
        ]);
        $response->assertSuccessful();

        $order = \json_decode($response->content())->data;

        $secondProduct = $this->products->random(1)->first();

        $response = $this->put("/api/orders/{$order->id}/add", [
            'product_id' => $secondProduct->id,
        ]);
        $response->assertSuccessful();

        $response->assertJson([
            'data' => [
                'id'       => $order->id,
                'products' => [
                    ['id' => $product->id,],
                    ['id' => $secondProduct->id],
                ],
                'value'    => $product->price + $secondProduct->price,
            ],
        ]);

        $this->assertDatabaseCount('order_products', 2);
    }

    public function test_order_payment_successful()
    {
        $customer = $this->customers->random(1)->first();
        $product = $this->products->random(1)->first();

        $response = $this->post('/api/orders', [
            'customer_id' => $customer->id,
            'product_id'  => $product->id,
        ]);
        $response->assertSuccessful();
        $order = \json_decode($response->content())->data;

        $mock = \Mockery::mock(PaymentService::class);
        $mock->shouldReceive('pay')->once()->andReturn('{"message": "Payment Successful"}');
        $this->instance(PaymentService::class, $mock);

        $response = $this->post("/api/orders/{$order->id}/pay");
        $response->assertSuccessful()
            ->assertJson(['message' => "Payment Successful"]);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payed' => true]);
    }


    public function test_order_payment_declined()
    {
        $customer = $this->customers->random(1)->first();
        $product = $this->products->random(1)->first();

        $response = $this->post('/api/orders', [
            'customer_id' => $customer->id,
            'product_id'  => $product->id,
        ]);
        $response->assertSuccessful();
        $order = \json_decode($response->content())->data;

        $mock = \Mockery::mock(PaymentService::class);
        $mock->shouldReceive('pay')->once()->andReturn('{"message": "Insufficient Funds"}');
        $this->instance(PaymentService::class, $mock);

        $response = $this->post("/api/orders/{$order->id}/pay");
        $response
            ->assertSuccessful()
            ->assertJson(['message' => "Insufficient Funds"]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payed' => false]);
    }
}
