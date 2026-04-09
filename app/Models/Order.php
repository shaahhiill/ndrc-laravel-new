<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'retailer_id',
        'wholesaler_id',
        'distributor_id',
        'status',
        'order_date',
        'scheduled_dispatch_date',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'scheduled_dispatch_date' => 'date',
    ];

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    public function wholesaler()
    {
        return $this->belongsTo(User::class, 'wholesaler_id');
    }

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
