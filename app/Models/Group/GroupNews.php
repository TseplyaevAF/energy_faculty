<?php

namespace App\Models\Group;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupNews extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    const PATH = 'group_news';

    public function group() {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
