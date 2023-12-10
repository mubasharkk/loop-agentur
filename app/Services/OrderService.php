<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProducts;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Response;
use Mockery\Exception;

class OrderService
{

    /**
     * @param  int  $id
     * @throw \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Order
     */
    public function getById(int $id): Order
    {
        return Order::findOrFail($id);
    }

    /**
     * @param  int    $customerId
     * @param  int[]  $productIds
     *
     * @return Order
     */
    public function createOrder(int $customerId, array $productIds): Order
    {
        $order = new Order([
            'customer_id' => $customerId,
            'payed'       => false,
        ]);

        $order->save();

        $this->addProductsToOder($order->id, $productIds);

        return $order;
    }

    public function addProductsToOder(int $orderId, array $productIds)
    {
        $items = array_map(function ($productId) use ($orderId) {
            return [
                'order_id'   => $orderId,
                'product_id' => $productId,
            ];
        }, $productIds);

        return OrderProducts::insert($items);
    }

    /**
     * @param  int    $id
     * @param  int[]  $productsId
     *
     * @throws \Exception
     */
    public function removeProductsFromOrder(int $id, array $productIds)
    {
        $order = $this->getById($id);
        /** @Note: can also check here if the given products id is in the list or not */
        if ($order->products->count() <= 1) {
            throw new \Exception(
                "Order can not be empty! Unable to remove the only product.",
                Response::HTTP_BAD_REQUEST
            );
        }

        return OrderProducts::where(
            ['order_id' => $order->id, 'product_id' => $productIds]
        )->delete();
    }

    public function pay(int $orderId)
    {
        $paymentService = new PaymentService();
        $order = $this->getById($orderId);

        if ($order->payed){
            throw new \Exception('Order is already payed!', Response::HTTP_BAD_REQUEST);
        }

        try {
            $paymentService->pay(
                $order->id,
                $order->customer->email_address,
                $order->totalValue()
            );
            $order->update(['payed' => true]);
        } catch (BadResponseException $ex) {
            $response = $ex->getResponse();
            throw new Exception(
                \json_decode($response->getBody()->getContents())?->message,
                $response->getStatusCode()
            );
        }
    }
}
