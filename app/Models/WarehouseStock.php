<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    protected $table = 'warehouse_stock';

    protected $fillable = [
        'product_id',
        'total_stock',
        'reserved_stock',
        'reorder_point',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
