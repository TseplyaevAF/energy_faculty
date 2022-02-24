<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Headman extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
