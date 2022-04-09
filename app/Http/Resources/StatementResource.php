<?php

namespace App\Http\Resources;

use App\Models\Statement\Statement;
use Illuminate\Http\Resources\Json\JsonResource;

class StatementResource extends JsonResource
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
            'control_form' => Statement::getControlForms()[$this->control_form],
            'lesson' => [
                'discipline' => $this->lesson->discipline->title,
                'group' => $this->lesson->group->title,
                'teacher' => [
                    'surname' => $this->lesson->teacher->user->surname,
                    'name' => $this->lesson->teacher->user->name,
                    'patronymic' => $this->lesson->teacher->user->patronymic,
                    'post' => $this->lesson->teacher->post,
                    'rank' => $this->lesson->teacher->rank,
                ],
                'semester' => $this->lesson->semester,
                'year' => $this->lesson->year->start_year . '-' . $this->lesson->year->end_year,
            ],
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date
        ];
    }
}
