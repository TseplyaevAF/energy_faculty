<?php

namespace App\Models\Student;

use App\Models\Group\Group;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = false;

    public function role(){
        return $this->hasOne(Role::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
