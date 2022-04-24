<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Student\Homework;
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

    // типы загружаемых работ
    public const TEST = 'КР';
    public const LEC = 'УМ';

    public static function getTasksTypes()
    {
        return [
            self::TEST => 'Контрольная работа',
            self::LEC => 'Учебный материал',
        ];
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    public function homework() {
        return $this->hasMany(Homework::class);
    }
}
