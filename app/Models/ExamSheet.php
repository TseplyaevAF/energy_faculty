<?php

namespace App\Models;

use App\Models\Statement\Individual;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSheet extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function individual() {
        return $this->belongsTo(Individual::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
