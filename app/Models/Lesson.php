<?php

namespace App\Models;

use App\Models\Group\Group;
use App\Models\Schedule\Schedule;
use App\Models\Statement\Statement;
use App\Models\Teacher\Task;
use App\Models\Teacher\Teacher;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory, Filterable;

    protected $guarded = false;

    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function year() {
        return $this->belongsTo(Year::class);
    }

    public function statements() {
        return $this->hasMany(Statement::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

    public function student_progress() {
        return $this->hasMany(StudentProgress::class);
    }
}
