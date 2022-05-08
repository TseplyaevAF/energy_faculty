<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'surname' => $this->user->surname,
            'name' =>  $this->user->name,
            'patronymic' => $this->user->patronymic,
            'post' => $this->post,
            'rank' => $this->rank,
            'chair' => $this->chair,
            'work_phone' => $this->work_phone,
            'link' => $this->link,
        ];
    }
}
