<?php

namespace App\Models\Student;

use App\Models\Group\Group;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Student extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasMediaTrait;

    protected $guarded = false;

    const PATH = 'students';

    public function role(){
        return $this->hasOne(Role::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
