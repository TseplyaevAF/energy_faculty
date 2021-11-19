<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use App\Models\Group\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Task extends Model
{
    protected $guarded = false;
    use HasFactory, SoftDeletes;

    const PATH = 'tasks';

    public function group() {
        return $this->belongsTo(Group::class);
    }
    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }
}
