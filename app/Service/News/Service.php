<?php


namespace App\Service\News;

use App\Models\News\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            if (isset($data['images'])) {
                $tempImages = [];
                foreach ($data['images'] as $image) {
                    $imagePath = 'images/news/categories/' . $data['category_id'];
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
            }
            News::firstOrCreate($data);
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
                $tempImages = [];
                // загружаем новые картинки
                foreach ($data['images'] as $image) {
                    $imagePath = 'images/news/categories/' . $data['category_id'];
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
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
