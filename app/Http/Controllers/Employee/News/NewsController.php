<?php

namespace App\Http\Controllers\Employee\News;

use App\Http\Controllers\Controller;
use App\Http\Filters\NewsFilter;
use App\Http\Requests\Employee\News\StoreRequest;
use App\Http\Requests\Employee\News\UpdateRequest;
use App\Http\Requests\News\FilterRequest;
use App\Models\News\Category;
use App\Models\News\News;
use App\Models\User;
use App\Service\News\Service;

class NewsController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(NewsFilter::class, ['queryParams' => array_filter($data)]);
        $charId = auth()->user()->role_id != User::ROLE_TEACHER ?
            auth()->user()->employee->chair_id : auth()->user()->teacher->chair_id;
        $all_news = News::where('chair_id', $charId)
            ->filter($filter)
            ->orderBy('updated_at', 'desc')
            ->paginate(5);
        $categories = Category::all();
        return view('employee.news.index',
            compact('all_news', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('employee.news.create', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->service->store($data);

        return redirect()->route('employee.news.index');
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        $images = json_decode($news->images);
        return view('employee.news.edit', compact('news', 'categories', 'images'));
    }

    public function update(UpdateRequest $request, News $news)
    {
        $data = $request->validated();

        $this->service->update($data, $news);

        return redirect()->back()->withSuccess('Запись успешно отредактирована');
    }

    public function delete(News $news)
    {
        $news->delete();
        return redirect()->back()->withSuccess('Запись была удалена');
    }
}
