<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'status' => $this->status,
            'status_label' => match($this->status) {
                'placed' => 'Order Placed',
                'payment_pending' => 'Awaiting Payment',
                'wholesaler_pending' => 'Awaiting Wholesaler Review',
                'wholesaler_accepted' => 'Accepted by Wholesaler',
                'distributor_pending' => 'Awaiting Distributor Confirmation',
                'distributor_confirmed' => 'Confirmed by Distributor',
                'dispatched' => 'Order Dispatched (In Transit)',
                'delivered' => 'Successfully Delivered',
                'rejected' => 'Order Rejected',
                default => str_replace('_', ' ', ucfirst($this->status)),
            },
            'total_amount' => (float) $this->total_amount,
            'formatted_total' => 'Rs ' . number_format($this->total_amount, 2),
            'order_date' => $this->order_date ? $this->order_date->toDateString() : null,
            'dispatch_date' => $this->scheduled_dispatch_date ? $this->scheduled_dispatch_date->toDateString() : null,
            'retailer' => [
                'id' => $this->retailer_id,
                'name' => $this->retailer->name ?? 'Unknown',
            ],
            'wholesaler' => $this->when($this->wholesaler_id, [
                'id' => $this->wholesaler_id,
                'name' => $this->wholesaler->name ?? 'Unknown',
            ]),
            'distributor' => [
                'id' => $this->distributor_id,
                'name' => $this->distributor->name ?? 'Unknown',
            ],
            'items' => $this->whenLoaded('items', function() {
                return $this->items->map(fn($item) => [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Unknown Product',
                    'quantity' => $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                    'subtotal' => (float) $item->subtotal,
                ]);
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
