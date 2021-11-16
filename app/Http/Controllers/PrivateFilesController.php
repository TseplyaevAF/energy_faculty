<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PrivateFilesController extends Controller
{
    public function get(User $user, $filename) {
        // проверим доступ пользователя к файлу
        // if (!auth()->user()) {
        //     return response()->json([], 403);
        // }

        // теперь выделим id модели из имени файла
        $filename = explode('.', pathinfo($filename, PATHINFO_FILENAME));
        $media_id = $filename[0];
        // забираем название конверсии из имени файла
        $conversion = $filename[1] ?? '';

        /** @var Media $media */
        // находим медиа-модель среди файлов сотрудника
        $media = $user->getMedia()->where('id', $media_id)->first();
        // и сервим файл из этой медиа-модели
        return isset($media) ? response()->file($media->getPath($conversion)) : abort(404);;
    }

    public function __construct()
    {
        // используем middleware чтобы автоматически отсечь анонимов
        $this->middleware('auth');
    }
}
