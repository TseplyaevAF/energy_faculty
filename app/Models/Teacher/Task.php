<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Task extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasMediaTrait;

    protected $guarded = false;

    const PATH = 'tasks';

    const ACTIVE = 0;
    const COMPLETED = 1;
    const CHECK = 2;

    public static function getStatusVariants()
    {
        return [
            self::ACTIVE => 'Открыто',
            self::COMPLETED => 'Завершено',
            self::CHECK => 'Закрыто на проверку',
        ];
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }
}
