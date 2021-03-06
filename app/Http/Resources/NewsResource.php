<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'preview' => $this->preview,
            'images' => json_decode($this->images),
            'created_at' => $this->created_at,
            'start_date' => isset($this->event) ? $this->event->start_date : null,
            'finish_date' => isset($this->event) ? $this->event->finish_date : null
        ];
    }
}
