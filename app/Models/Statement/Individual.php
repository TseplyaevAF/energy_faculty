<?php

namespace App\Models\Statement;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
