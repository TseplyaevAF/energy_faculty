<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupNews extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = false;

    public function group() {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
