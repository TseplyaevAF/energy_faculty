<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'student_id_number' => $this->student_id_number,
            'FIO' => $this->user->surname . ' ' . $this->user->name . ' ' . $this->user->patronymic,
            'phone' => $this->user->phone_number,
            'headman' => $this->id == $this->group->headman
        ];
    }
}
