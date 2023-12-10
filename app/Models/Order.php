<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payed'
    ];

    protected $casts = [
        'payed' => 'bool'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            OrderProducts::class,
            'order_id',
            'id',
            'id',
            'product_id'
        );
    }

    public function totalValue(): float
    {
        return round($this->products->sum('price'), 2);
    }
}
