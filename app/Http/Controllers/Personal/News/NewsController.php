<?php

namespace App\Http\Controllers\Personal\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\Personal\News\StoreRequest;
use App\Http\Requests\Personal\News\UpdateRequest;
use App\Models\Group\GroupNews;
use App\Models\Student\Headman;
use App\Service\Group\News\Service;
use Illuminate\Support\Facades\Gate;

class NewsController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        Gate::authorize('index-homework');
        $group_news = GroupNews::all()->sortByDesc('updated_at')->values()->where('group_id', auth()->user()->student->group_id);
        return view('personal.news.index', compact('group_news'));
    }

    public function create()
    {
        Gate::authorize('create-group-news');

        return view('personal.news.create');
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('create-group-news');

        $data = $request->validated();

        $this->service->store($data);

        return redirect()->route('personal.news.index');
    }

    public function edit(GroupNews $news)
    {
        Gate::authorize('create-group-news');
        Gate::authorize('edit-group-news', [$news]);

        $images = json_decode($news->images);
        return view('personal.news.edit', compact('news', 'images'));
    }

    public function update(UpdateRequest $request, GroupNews $news)
    {
        Gate::authorize('create-group-news');
        Gate::authorize('edit-group-news', [$news]);

        $data = $request->validated();

        $news = $this->service->update($data, $news);

        return redirect()->route('admin.group.news.show', compact('news'));
    }

    public function delete(GroupNews $news)
    {
        Gate::authorize('create-group-news');
        Gate::authorize('edit-group-news', [$news]);

        $news->delete();
        return redirect()->route('admin.group.news.index');
    }
}
