<?php

namespace App\Models\Teacher;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }
}
