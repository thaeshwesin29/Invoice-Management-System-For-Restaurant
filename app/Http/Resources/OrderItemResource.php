<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'order_id' => $this->order->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'quantity' => number_format($this->quantity),
            'price' => number_format($this->price) . ' ' . __('message.mmk'),
        ];
    }
}
