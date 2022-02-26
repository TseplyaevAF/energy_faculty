<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function disciplines() {
        return $this->belongsToMany(Discipline::class, 'lessons', 'teacher_id', 'discipline_id');
    }

    public function groups() {
        return $this->belongsToMany(Group::class, 'lessons', 'teacher_id', 'group_id');
    }
}
