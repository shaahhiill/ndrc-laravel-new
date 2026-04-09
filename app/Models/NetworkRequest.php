<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkRequest extends Model
{
    protected $fillable = [
        'retailer_id',
        'wholesaler_id',
        'status',
        'message',
    ];

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    public function wholesaler()
    {
        return $this->belongsTo(User::class, 'wholesaler_id');
    }
}
