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
        'payment_method',
        'stripe_session_id',
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

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'placed' => 'Order Placed',
            'payment_pending' => 'Awaiting Payment',
            'wholesaler_pending' => 'Pending Wholesaler Review',
            'wholesaler_accepted' => 'Approved by Wholesaler',
            'distributor_pending' => 'Pending Distributor Review',
            'distributor_confirmed' => 'Confirmed by Distributor',
            'dispatched' => 'Dispatched (In Transit)',
            'delivered' => 'Delivered',
            'rejected' => 'Rejected',
            default => str_replace('_', ' ', ucfirst($this->status)),
        };
    }
}
