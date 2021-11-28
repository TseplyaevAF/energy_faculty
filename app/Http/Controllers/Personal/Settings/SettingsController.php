<?php

namespace App\Http\Controllers\Personal\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Personal\Settings\UpdateRequest;
use App\Models\User;
use App\Service\User\Settings\Service;
use Spatie\MediaLibrary\Models\Media;

class SettingsController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function edit() {
        $user = auth()->user();
        return view('personal.settings.edit', compact('user'));
    }

    public function update(UpdateRequest $request, User $user) {
        $data = $request->validated();
        try {
            $this->service->update($data, $user);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->back()->withSuccess('Данные аккаунта успешно обновлены!');
    }

    public function showImage($userId, $mediaId, $filename) {
        $user = User::find($userId);
        $media = $user->getMedia('avatar')->where('id', $mediaId)->first();
        // сервим файл из медиа-модели
        return isset($media) ? response()->file($media->getPath(), [
            'Cache-Control' => 'no-cache, no-cache, must-revalidate',
            ]) : abort(404);
    }
}
