<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'discipline' => $this->discipline->title,
            'group' => $this->group->title,
            'teacher' => ($this->teacher != null) ? $this->teacher->user->fullName() : null,
            'semester' => $this->semester,
            'year' => $this->year->start_year . '-' . $this->year->end_year,
        ];
    }
}
