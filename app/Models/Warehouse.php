<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'location',
        'capacity',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
