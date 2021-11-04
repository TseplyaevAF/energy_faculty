<?php

namespace App\Models;

use App\Models\Group\Group;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    

    protected $guarded = false;

    public function week_type() {
        return $this->belongsTo(WeekType::class);
    }

    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function class_type() {
        return $this->belongsTo(ClassType::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function day() {
        return $this->belongsTo(Day::class);
    }
}
