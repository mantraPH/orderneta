<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //transforms the resource into an array made up of the attributes to be converted to JSON
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'address' => $this->address,
            'phone'=>$this->phone,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'user' => $this->user,
            // 'products' => $this->products,
        ];

    }
}
