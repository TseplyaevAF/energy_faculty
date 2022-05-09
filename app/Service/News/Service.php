<?php


namespace App\Service\News;

use App\Models\News\Event;
use App\Models\News\News;
use App\Models\Olimp;
use App\Models\OlimpType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $news = News::firstOrCreate([
                'title' => $data['title'],
                'content' => $data['content'],
                'category_id' => $data['category_id'],
                'chair_id' => $data['chair_id']
            ]);
            if (isset($data['tags_ids'])) {
                $news->tags()->attach($data['tags_ids']);
            }

            $imagePath = 'images/news/' . $news->id;

            $data['preview'] = Storage::disk('public')
                ->put($imagePath . '/preview', $data['preview']);
            $news->update([
                'preview' => $data['preview']
            ]);

            if (isset($data['images'])) {
                $news->update([
                    'images' => $this->loadImages($imagePath, $data['images'])
                ]);
            }

            if (isset($data['start_date']) || isset($data['finish_date'])) {
                $news->update([
                    'event_id' => $this->createEvent($data['start_date'], $data['finish_date'])->id
                ]);
            }

            if (isset($data['olimp_type'])) {
                Olimp::firstOrCreate([
                    'olimp_type_id' => $data['olimp_type'],
                    'news_id' => $news->id,
                    'year' => date('Y'),
                ]);
            }
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
            $news->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'category_id' => $data['category_id']
            ]);
            $news->tags()->sync(isset($data['tags_ids']) ? $data['tags_ids'] : null);

            if (isset($news->event)) {
                $news->event->update([
                    'start_date' => isset($data['start_date']) ? str_replace("-",'.',$data['start_date']) : null,
                    'finish_date' => isset($data['finish_date']) ? str_replace("-",'.',$data['finish_date']) : null
                ]);
            }
            $imagePath = 'images/news/' . $news->id;

            if (isset($data['preview'])) {
                Storage::disk('public')->delete($news->preview);
                $data['preview'] = Storage::disk('public')
                    ->put($imagePath . '/preview', $data['preview']);
                $news->update([
                    'preview' => $data['preview']
                ]);
            }

            if (isset($data['images'])) {
                // загружаем новые картинки
                $data['images'] = $this->loadImages($imagePath, $data['images']);
                if (isset($news->images)) {
                    // удаляем старые картинки
                    foreach (json_decode($news->images) as $image) {
                        Storage::disk('public')->delete($image);
                    }
                }
            } else {
                // удаляем urls картинок из бд
                $data['images'] = null;
                // удаляем пути к файлам с сервера
                if (isset($news->images))
                    foreach (json_decode($news->images) as $image) {
                        Storage::disk('public')->delete($image);
                    }
            }
            $news->update([
                'images' => $data['images']
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $news;
    }

    private function loadImages($imagesPath, $images) {
        $tempImages = [];
        foreach ($images as $image) {
            $tempImages[] = Storage::disk('public')
                ->put($imagesPath, $image);
        }
        return json_encode($tempImages);
    }

    private function createEvent($start_date, $finish_date) {
        return Event::firstOrcreate([
            'start_date' => isset($start_date) ? str_replace("-",".",$start_date) : null,
            'finish_date' => isset($finish_date) ? str_replace("-",".",$finish_date) : null
        ]);
    }
}
