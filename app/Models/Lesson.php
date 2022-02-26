<?php

namespace App\Models;

use App\Models\Group\Group;
use App\Models\Teacher\Task;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

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

    public function tasks() {
        return $this->hasMany(Task::class);
    }
}
