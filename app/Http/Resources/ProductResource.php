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
            'category_name' => optional($this->category)->name,
            'price' => number_format($this->price) . ' ' . __('message.mmk'),
            'stock_quantity' => number_format($this->stock_quantity),
            'image_url' => $this->image_url,
        ];
    }
}
