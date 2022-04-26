<?php


namespace App\Service\Group\News;

use App\Models\Group\Group;
use App\Models\Group\GroupNews;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $student = auth()->user()->student;
            $data['group_id'] = $student->group_id;
            $data['user_id'] = $student->user->id;
            if (isset($data['images'])) {
                $tempImages = [];
                foreach ($data['images'] as $image) {
                    $imagePath = 'images/groups/' . $data['group_id'] . '/news';
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
            }
            $post = GroupNews::firstOrCreate($data);
            $group = Group::find($data['group_id']);
            $post->unread_posts()->attach($group->students);
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
                    $imagePath = 'images/groups/' . $news->group->id . '/news';
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
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
