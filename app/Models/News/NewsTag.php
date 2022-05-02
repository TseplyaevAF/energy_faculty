<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTag extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = false;
}
