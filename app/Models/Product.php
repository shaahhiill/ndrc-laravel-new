<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category',
        'unit',
        'description',
        'image_url',
        'price',
    ];

    public function stock()
    {
        return $this->hasOne(WarehouseStock::class);
    }
}
