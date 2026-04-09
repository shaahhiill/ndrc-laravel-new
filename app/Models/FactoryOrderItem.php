<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactoryOrderItem extends Model
{
    protected $fillable = [
        'factory_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(FactoryOrder::class, 'factory_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
