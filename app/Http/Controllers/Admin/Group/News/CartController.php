<?php

namespace App\Http\Controllers\Admin\Group\News;

use App\Http\Controllers\Controller;
use App\Models\Group\Group;
use App\Models\Group\GroupNews;

class CartController extends Controller
{
    public function index()
    {
        $group_news = GroupNews::onlyTrashed()->get();
        $groups = Group::all();
        return view('admin.group.news.cart.index', compact('group_news', 'groups'));
    }

    public function show($newsId)
    {
        $news = GroupNews::withTrashed()->find($newsId);
        return view('admin.group.news.cart.show', compact('news'));
    }

    public function restore($newsId) {
        $news = GroupNews::withTrashed()->find($newsId);
        $news->restore();
        return redirect()->route('admin.group.news.cart.index');
    }

    public function delete($newsId)
    {
        $news = GroupNews::withTrashed()->find($newsId);
        $news->forceDelete();
        return redirect()->route('admin.group.news.cart.index');
    }
}