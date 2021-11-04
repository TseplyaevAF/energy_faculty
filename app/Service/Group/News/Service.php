<?php


namespace App\Service\Group\News;

use App\Models\Group\GroupNews;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $imagePath = 'images/groups/' . $data['group_id'] . '/news';
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
            }
            GroupNews::firstOrCreate($data);
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
                foreach ($data['images'] as $image) {
                    $imagePath = 'images/groups/' . $news->group->id . '/news';
                    $tempImages[] = Storage::disk('public')->put($imagePath, $image);
                }
                $data['images'] = json_encode($tempImages);
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
