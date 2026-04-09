<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'vehicle_type',
        'status',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
