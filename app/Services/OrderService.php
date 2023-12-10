<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProducts;

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

        $items = array_map(function ($productId) use ($order) {
            return [
                'order_id'   => $order->id,
                'product_id' => $productId,
            ];
        }, $productIds);

        OrderProducts::insert($items);

        return $order;
    }
}
