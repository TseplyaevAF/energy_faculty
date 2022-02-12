<?php

namespace App\Http\Controllers\Employee\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\File\StoreRequest;
use App\Http\Requests\Employee\File\UpdateRequest;
use App\Http\Requests\Employee\File\FilterRequest;
use App\Models\Category;
use App\Models\Employee;
use App\Models\News;
use App\Service\File\Service;
use Spatie\MediaLibrary\Models\Media;

class FileController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        $data = $request->validated();
        $employee = auth()->user()->employee;
        $categories = Employee::getFilesCategories();
        $documents = $employee->getMedia('documents')->map(function (Media $media) {
            return $media;
        });
        $images = $employee->getMedia('images')->map(function (Media $media) {
            return $media;
        });
        $archives = $employee->getMedia('archives')->map(function (Media $media) {
            return $media;
        });
        $files = array_merge($documents->toArray(), $images->toArray(), $archives->toArray());
        // return redirect()->route('file.get', [$employee, 'filename' => $files[0]->id]);

        return view('employee.file.index', compact('categories', 'files'));
        // dd($categories);
    }

    public function show(Employee $employee, $collectionName, $mediaId, $filename) {
        // $this->middleware('auth');
        // $url = Crypt::decryptString($url);
        // $url = explode('/', $url);

        // теперь выделим id модели из имени файла
        $filename = explode('.', pathinfo($filename, PATHINFO_FILENAME));

        // забираем название конверсии из имени файла
        $conversion = $filename[1] ?? '';

        /** @var Media $media */
        // находим медиа-модель среди файлов сотрудника
        $media = $employee->getMedia($collectionName)->where('id', $mediaId)->first();

        // и сервим файл из этой медиа-модели
        return isset($media) ? response()->file($media->getPath($conversion)) : abort(404);
    }

    public function upload(StoreRequest $request)
    {
        $data = $request->validated();

        $this->service->store(auth()->user()->employee, $data);

        return redirect()->route('employee.file.index');
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
