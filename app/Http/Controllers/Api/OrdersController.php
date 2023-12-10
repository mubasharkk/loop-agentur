<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    /**
     * @var \App\Services\OrderService
     */
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the orders of the specified customer.
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|int|exists:customers,id',
        ]);

        return OrderResource::collection(
            Customer::findOrFail($data['customer_id'])->orders
        );
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|int|exists:customers,id',
            'product_id'  => 'required|int|exists:products,id',
        ]);

        $order = $this->orderService->createOrder(
            $data['customer_id'],
            [$data['product_id']]
        );

        return new OrderResource($order);
    }

    /**
     * Display the specified order.
     */
    public function show(int $id)
    {
        return new OrderResource(
            $this->orderService->getById($id)
        );
    }

    /**
     * Update the specified products in an order.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'product_id' => 'required|int|exists:products,id',
        ]);

        $order = $this->orderService->getById($id);

        /** @Note: This is just a workaround to reduce the time spent on this task */
        if ($request->isMethod('PUT')) {
            $this->orderService->addProductsToOder($order->id, [$data['product_id']]);
        }elseif($request->isMethod('DELETE')) {
            $this->orderService->removeProductsFromOrder($order->id, [$data['product_id']]);
        }

        return new OrderResource($order);
    }

    /**
     * Remove the specified order.
     */
    public function destroy(string $id)
    {
        return response(
            $this->orderService->getById($id)->delete()
        );
    }
}
