<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payed'
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderProducts::class);
    }
}
