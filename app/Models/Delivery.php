<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'delivery_address',
        'warehouse_id',
        'driver_id',
        'status',
        'estimated_delivery',
        'actual_delivery',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
