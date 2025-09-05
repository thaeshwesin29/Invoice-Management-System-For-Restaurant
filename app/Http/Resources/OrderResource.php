<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\OrderItemResource;
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
        $order_items = optional($this->order_items);

        $total_product = $order_items ? $order_items->count() : 0;
        $total_price = $order_items ? $order_items->sum('price'): 0;
        $total_quantity = $order_items ? $order_items->sum('quantity') : 0;
        $tax = ($total_price*5)/100;
        $total_amount = $total_price + $tax;

        return [
            'id' => $this->id,
            'orderable' => $this->orderable,
            'order_items' => $order_items ? OrderItemResource::collection($order_items) : [],
            'total_product' => number_format($total_product),
            'total_price' => number_format($total_price) . ' ' . __('message.mmk'),
            'total_quantity' => number_format($total_quantity),
            'tax' => number_format($tax) . ' ' . __('message.mmk'),
            'total_amount' => number_format($total_amount) . ' ' . __('message.mmk'),
        ];
    }
}
