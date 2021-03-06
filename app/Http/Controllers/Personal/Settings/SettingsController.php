<?php

namespace App\Http\Controllers\Personal\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Personal\Settings\UpdateMainRequest;
use App\Http\Requests\Personal\Settings\UpdatePasswordRequest;
use App\Models\User;
use App\Service\User\Settings\Service;
use Illuminate\Http\Request;

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

    public function updateMain(UpdateMainRequest $request, User $user) {
        $data = $request->validated();
        try {
            $this->service->updateMain($data, $user);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->back()->withSuccess('Данные аккаунта успешно обновлены!');
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user) {
        $data = $request->validated();
        try {
            $this->service->updatePassword($data, $user);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->back()->withSuccess('Пароль был успешно обновлен!');
    }

    public function changeSecurity(User $user) {
        if ($user->is_active_2fa) {
            $user->update([
                'is_active_2fa' => false
            ]);
            return redirect()->back()->withSuccess('Двухфакторная аутентификация отключена');
        } else {
            $user->update([
                'is_active_2fa' => true
            ]);
            return response('Двухфакторная аутентификация включена. При каждом входе вам будет приходить звонок на телефон', 200);
        }
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
