<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'driver_id',
        'name',
        'status',
        'total_distance',
        'total_duration',
        'optimized_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'optimized_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function stops()
    {
        return $this->hasMany(DeliveryStop::class, 'route_id')->orderBy('sequence_number');
    }
}
