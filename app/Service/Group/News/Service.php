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
            $data['user_id'] = auth()->user()->id;
            if (isset($data['images'])) {
                $tempImages = [];
                foreach ($data['images'] as $image) {
                    $imagePath = 'images/groups/' . $data['group_id'] . '/news';
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
            }
            $post = GroupNews::create($data);
            $group = Group::find($data['group_id']);
            $users = [];
            // формирование списка непросмотренных постов для всех студентов группы...
            foreach ($group->students as $student) {
                $users[$student->user->id] = $student->user->id;
            }
            //... а также куратора
            if (isset($group->teacher) && $data['user_id'] != $group->teacher->user->id) {
                $users[$group->teacher->user->id] = $group->teacher->user->id;
            }
            // исключить автора поста из списка "непросмотренных" постов
            unset($users[auth()->user()->id]);
            $post->unread_posts()->attach($users);
            DB::commit();
            return $post;
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
            } else if (isset($news->images)) {
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

    public function delete($news) {
        try {
            DB::beginTransaction();

            if (isset($news->images)) {
                foreach (json_decode($news->images) as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $news->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }
}
