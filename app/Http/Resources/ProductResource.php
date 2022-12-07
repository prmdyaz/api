<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'src_img' => url('')."/product/img/".$this->img,
            'product' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'product_sold' => $this->sold
        ];
    }
}
