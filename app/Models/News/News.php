<?php

namespace App\Models\News;

use App\Models\Chair;
use App\Models\Olimp;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $guarded = false;

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'news_tags', 'news_id', 'tag_id');
    }

    public function chairs() {
        return $this->belongsToMany(Chair::class, 'news_chairs', 'news_id', 'chair_id');
    }

    public function olimp() {
        return $this->hasOne(Olimp::class);
    }
}
