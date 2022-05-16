<?php

namespace App\Models;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;
    protected $guarded = false;
    public $timestamps = false;

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
