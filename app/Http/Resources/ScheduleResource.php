<?php

namespace App\Http\Resources;

use App\Models\Schedule\Schedule;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'day' => Schedule::getDays()[$this->day],
            'week_type' => Schedule::getWeekTypes()[$this->week_type],
            'class_time' => [
                'start_time' => $this->class_time->start_time,
                'end_time' => $this->class_time->end_time,
            ],
            'class_type' => $this->class_type->title,
            'classroom' => [
                'corps' => $this->classroom->corps,
                'cabinet' => $this->classroom->cabinet,
            ],
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
                'year' => $this->lesson->year,
            ]
        ];
    }
}
