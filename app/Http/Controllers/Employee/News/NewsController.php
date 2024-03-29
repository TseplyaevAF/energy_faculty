<?php

namespace App\Http\Controllers\Employee\News;

use App\Http\Controllers\Controller;
use App\Http\Filters\NewsFilter;
use App\Http\Requests\Employee\News\StoreRequest;
use App\Http\Requests\Employee\News\UpdateRequest;
use App\Http\Requests\News\FilterRequest;
use App\Models\Chair;
use App\Models\News\Category;
use App\Models\News\News;
use App\Models\News\Tag;
use App\Models\OlimpType;
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
        $all_news = News::with('chairs')
            ->where('chair_id', session('chair')['id'])
            ->filter($filter)
            ->orderBy('updated_at', 'desc')
            ->paginate(5);
        $categories = Category::all();
        return view('employee.news.index',
            compact('all_news', 'categories'));
    }

    public function create(Category $category, $olimpType, $news)
    {
        $tags = Tag::all();
        $chairs = Chair::where('id', '!=', session('chair')['id'])->get();
        if ($news != 'null') {
            $news = News::findOrFail($news);
        }
        if ($olimpType != 'null') {
            $olimpType = OlimpType::findOrFail($olimpType);
        }
        return view('employee.news.create', compact('category', 'tags', 'chairs', 'olimpType', 'news'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->service->store($data);

        return redirect()->route('employee.news.index');
    }

    public function edit(News $news)
    {
        $tags = Tag::all();
        $images = json_decode($news->images);
        $chairs = Chair::where('id', '!=', session('chair')['id'])->get();
        return view('employee.news.edit', compact('news', 'images', 'tags', 'chairs'));
    }

    public function update(UpdateRequest $request, News $news)
    {
        $data = $request->validated();

        $this->service->update($data, $news);

        return redirect()->back()->withSuccess('Запись успешно отредактирована');
    }

    public function destroy(News $news)
    {
        $news->forceDelete();
        return redirect()->back()->withSuccess('Запись была удалена');
    }

    public function addToSlider(News $news) {
        if (!$news->is_slider_item) {
            $news->update(['is_slider_item' => true]);
        } else {
            $news->update(['is_slider_item' => false]);
        }
        return response('', 200);
    }
}
