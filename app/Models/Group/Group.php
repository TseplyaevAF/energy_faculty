<?php

namespace App\Models\Group;

use App\Models\Chair;
use App\Models\Discipline;
use App\Models\Lesson;
use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = false;

    public function getHeadman() {
        if (isset($this->headman)) {
            return $this->students->where('id', $this->headman)->first();
        }
        return null;
    }

    public function students() {
        return $this->hasMany(Student::class);
    }

    public function chair() {
        return $this->belongsTo(Chair::class);
    }

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function disciplines() {
        return $this->belongsToMany(Discipline::class, 'lessons', 'group_id', 'discipline_id');
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function news() {
        return $this->hasMany(GroupNews::class);
    }
}
