<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_method_id' => $this->id,
            'src_img' => url('')."/payment/img/".$this->img,
            'name' => $this->name,
            'fee' => $this->fees,
            'wallet' => $this->wallet
        ];
    }
}
