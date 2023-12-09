<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{

    /**
     * @param  int  $id
     * @throw \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return mixed
     */
    public function getById(int $id)
    {
        return Order::findOrFail($id);
    }
}
