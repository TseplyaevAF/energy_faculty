<?php

namespace App\Models\Teacher;

use App\Models\Group\GroupDiscipline;
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group_disciplines()
    {
        return $this->belongsToMany(GroupDiscipline::class, 'teacher_group_disciplines', 'teacher_id', 'group_discipline_id');
    }
}
