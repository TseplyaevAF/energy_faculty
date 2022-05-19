<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupNewsResource;
use App\Models\Group\GroupNews;

class GroupNewsController extends Controller
{
    public function index()
    {
        return GroupNewsResource::collection(GroupNews::paginate(20));
    }
}
