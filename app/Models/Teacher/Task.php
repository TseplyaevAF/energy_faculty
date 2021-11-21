<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Student\Homework;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    protected $guarded = false;
    use HasFactory, SoftDeletes;

    const PATH = 'tasks';
    const ACTIVE = 0;
    const COMPLETED = 1;

    public static function getStatusVariants()
    {
        return [
            self::ACTIVE => 'Открыто',
            self::COMPLETED => 'Завершено',
        ];
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
    
    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }
}
