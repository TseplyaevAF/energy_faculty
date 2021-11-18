<?php


namespace App\Service\File;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($employee, $data)
    {
        try {
            DB::beginTransaction();
            $filename = $data['file']->getClientOriginalName();
            $titleCollection = $this->getTitleCollection($filename);
            $employee->addMedia($data['file'])->toMediaCollection($titleCollection);
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

    private function getTitleCollection($filename)
    {
        $filename = explode('.', $filename);
        $documentsTypes = ['doc', 'docx', 'xls', 'xlsx', 'txt', 'pdf'];
        $archivesTypes = ['rar', 'zip'];
        $imagesTypes = ['jpg', 'jpeg', 'png'];
        if (in_array($filename[1], $documentsTypes)) {
            return 'documents';
        } else if (in_array($filename[1], $archivesTypes)) {
            return 'archives';
        }
        else if (in_array($filename[1], $imagesTypes)) {
            return 'images';
        } else {
            throw new Exception('File extension not defined', 404);
        }
    }
}
