<?php

namespace App\Models\Group;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupNews extends Model
{
    use HasFactory;

    protected $guarded = false;

    const PATH = 'group_news';

    public function group() {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function unread_posts() {
        return $this->belongsToMany(User::class, 'unread_posts', 'group_news_id', 'user_id');
    }

    public function getAuthor() {
        if (isset($this->user->teacher) && $this->user->teacher->id == $this->group->teacher->id) {
            return $this->user->fullName() . ' (куратор)';
        }
        if (isset($this->user->student) && $this->user->student->id == $this->group->headman) {
            return $this->user->fullName() . ' (староста)';
        }
        return $this->user->fullName();
    }
}
