<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactoryOrder extends Model
{
    protected $fillable = [
        'order_number',
        'distributor_id',
        'status',
        'total_amount',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function items()
    {
        return $this->hasMany(FactoryOrderItem::class);
    }
}
