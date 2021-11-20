<?php


namespace App\Service\Homework;

use App\Models\Student\Homework;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($student, $data)
    {
        try {
            DB::beginTransaction();
            $media = $student->addMedia($data['homework'])->toMediaCollection(Homework::PATH);
            $data['homework'] = $media->getUrl();
            $data['student_id'] = $student->id;
            Homework::firstOrCreate($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $news)
    {
        try {
            DB::beginTransaction();
            if (isset($data['images'])) {
                // загружаем новые картинки
                foreach ($data['images'] as $image) {
                    $imagePath = 'images/news/categories/' . $data['category_id'];
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
                // загружаем старые картинки в другой каталог, 
                // если они не были удалены 
                
                // удаляем старые
                if (isset($news->images))
                foreach (json_decode($news->images) as $image) {
                    Storage::disk('public')->delete($image);
                }
            } else {
                // удаляем urls картинок из бд
                $data['images'] = null;
                // удаляем файлы картинок с сервера
                foreach (json_decode($news->images) as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $news->update($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $news;
    }
}