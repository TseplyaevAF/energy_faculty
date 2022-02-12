<?php

namespace App\Models\Teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class TeacherGroupDiscipline extends Model implements HasMedia
{
    use HasFactory, HasMediaTrait;

    const PATH = 'teacher_group_disciplines';
}
