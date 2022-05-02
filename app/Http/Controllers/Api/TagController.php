<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\News\Tag;

class TagController extends Controller
{
    public function index()
    {
        return TagResource::collection(Tag::all());
    }
}
