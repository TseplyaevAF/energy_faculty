<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homework extends Model
{
    protected $guarded = false;
    
    use HasFactory, SoftDeletes;

    const PATH = 'homework';

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function getHomework($taskId) {
        return Homework::where('task_id', $taskId)->get();
    }
}
