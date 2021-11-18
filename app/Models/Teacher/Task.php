<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use App\Models\Group\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function group() {
        return $this->belongsTo(Group::class);
    }
    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }
}
