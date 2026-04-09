<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'category' => $this->category,
            'description' => $this->description,
            'unit' => $this->unit,
            'price' => (float) $this->price,
            'formatted_price' => 'Rs ' . number_format($this->price, 2),
            'stock_available' => $this->whenLoaded('stock', function() {
                return $this->stock->total_stock ?? 0;
            }),
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
