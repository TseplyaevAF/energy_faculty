<?php

namespace App\Models;

use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
