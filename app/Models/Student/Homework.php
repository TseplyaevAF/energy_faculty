<?php

namespace App\Models\Student;

use App\Models\Teacher\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Homework extends Model implements HasMedia
{
    protected $guarded = false;

    use HasFactory, SoftDeletes, HasMediaTrait;

    const PATH = 'homework';

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function getHomework($taskId) {
        return Homework::where('task_id', $taskId)->get();
    }

    public function task() {
        return $this->belongsTo(Task::class);
    }
}
