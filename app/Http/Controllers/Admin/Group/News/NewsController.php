<?php

namespace App\Http\Controllers\Admin\Group\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Group\News\StoreRequest;
use App\Http\Requests\Admin\Group\News\UpdateRequest;
use App\Models\Group\Group;
use App\Models\Group\GroupNews;
use App\Service\Group\News\Service;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $group_news = DB::table('group_news')->orderBy('updated_at', 'desc')->where('deleted_at', null)->get();
        $groups = Group::all();
        return view('admin.group.news.index', compact('group_news', 'groups'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('admin.group.news.create', compact('groups'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->service->store($data);

        return redirect()->route('admin.group.news.index');
    }

    public function show(GroupNews $news)
    {
        $images = json_decode($news->images);
        return view('admin.group.news.show', compact('news', 'images'));
    }

    public function edit(GroupNews $news)
    {
        $groups = Group::all();
        $images = json_decode($news->images);
        return view('admin.group.news.edit', compact('news', 'groups', 'images'));
    }

    public function update(UpdateRequest $request, GroupNews $news)
    {
        $data = $request->validated();

        $news = $this->service->update($data, $news);

        return redirect()->route('admin.group.news.show', compact('news'));
    }

    public function delete(GroupNews $news)
    {
        $news->delete();
        return redirect()->route('admin.group.news.index');
    }
}
