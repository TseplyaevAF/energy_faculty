<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChairResource extends JsonResource
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
            'full_title' => $this->full_title,
            'title' => $this->title,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'cabinet' => $this->cabinet,
            'video' => $this->video,
            'description' => $this->description,
        ];
    }
}
