<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'order_id',
        'sequence_number',
        'estimated_arrival',
        'actual_arrival',
        'status',
        'notes',
    ];

    protected $casts = [
        'estimated_arrival' => 'datetime',
        'actual_arrival' => 'datetime',
    ];

    public function route()
    {
        return $this->belongsTo(DeliveryRoute::class, 'route_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
