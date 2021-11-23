<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Teacher extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasMediaTrait;

    const PATH = 'teachers';

    protected $guarded = false;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function disciplines() {
        return $this->belongsToMany(Discipline::class, 'teacher_disciplines', 'teacher_id', 'discipline_id');
    }
}
