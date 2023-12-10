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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return new OrderResource(
            $this->orderService->getById($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(
            $this->orderService->getById($id)->delete()
        );
    }
}
