<?php


namespace App\Service\Task;

use App\Models\Group\GroupDiscipline;
use App\Models\Teacher\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($teacher, $data)
    {
        try {
            DB::beginTransaction();
            $media = $teacher->addMedia($data['task'])->toMediaCollection(Task::PATH);
            $data['task'] = $media->getUrl();
            $data['teacher_id'] = $teacher->id;
            $groupDiscipline = GroupDiscipline::where('id', $data['group_discipline_id'])->first();
            $data['discipline_id'] = $groupDiscipline->discipline->id;
            $data['group_id'] = $groupDiscipline->group->id;
            unset($data['group_discipline_id']);
            Task::firstOrCreate($data);
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
