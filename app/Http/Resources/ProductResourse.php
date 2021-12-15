<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = null;

        if (!empty($this->image) && Storage::exists('public/product/' . $this->image)) {
            $image = asset('storage/product/' .  $this->image);
        }

        return [
            'id' => $this->id,
            'category' => [
                'id' => $this->getCategory->id,
                'name' => $this->getCategory->name
            ],
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'sku' => $this->sku,
            'image' => $image,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
