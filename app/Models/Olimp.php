<?php

namespace App\Models;

use App\Models\News\News;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Olimp extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function olimp_type() {
        return $this->belongsTo(OlimpType::class);
    }
}
