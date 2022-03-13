<?php

namespace App\Models\Statement;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    use HasFactory;

    protected $guarded = false;

    static public function getSignature($individual) {
        return [
            'Студент' => $individual['studentFIO'],
            '№ студенческого билета: ' => $individual['student_id_number']
        ];
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function statement() {
        return $this->belongsTo(Statement::class);
    }
}
