<?php

namespace App\Models;

use App\Models\Group\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chair extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = false;

    public function groups() {
        return $this->hasMany(Group::class);
    }
}
