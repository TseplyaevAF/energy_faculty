<?php

namespace App\Http\Controllers\Employee\News;

use App\Http\Controllers\Controller;
use App\Http\Filters\NewsFilter;
use App\Http\Requests\Employee\News\StoreRequest;
use App\Http\Requests\Employee\News\UpdateRequest;
use App\Http\Requests\News\FilterRequest;
use App\Models\Category;
use App\Models\News;
use App\Service\News\Service;
use Illuminate\Support\Facades\DB;

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
        $all_news = News::where('chair_id', auth()->user()->employee->chair_id)->filter($filter)->paginate(5);
        $categories = Category::all();
        return view('employee.news.index', compact('all_news', 'categories'));

        // $all_news = DB::table('news')->orderBy('updated_at', 'desc')->where('deleted_at', null)->get();
        // $categories = Category::all();
        // return view('employee.news.index', compact('all_news', 'categories'));
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

        $news = $this->service->update($data, $news);

        return redirect()->back()->withSuccess('Запись успешно отредактирована');
    }

    public function delete(News $news)
    {
        dd(1);
        $news->delete();
        return redirect()->route('admin.group.news.index');
    }
}
