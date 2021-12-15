<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product->name
            ],
            'trx_date' => $this->trx_date,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'update_at' => $this->update_at,
        ];
    }
}
