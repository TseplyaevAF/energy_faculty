<?php


namespace App\Service\User\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Service
{
    public function updateMain($data, $user) {
        try {
            DB::beginTransaction();
            if (isset($data['avatar'])) {
                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
                $user->getMedia('avatar')->count();
                $data['avatar'] = $user->getFirstMediaUrl('avatar');
            }
            if ($data['no_photo'] == -1 && ($user->avatar)) {
                $user->getMedia('avatar')->first()->delete();
                $data['avatar'] = null;
            }
            unset($data['no_photo']);
            $user->update($data);
            if (isset($user->teacher)) {
                $user->teacher->update([
                    'work_phone' => $data['work_phone']
                ]);
            }
            if (isset($user->student)) {
                $user->student->update([
                    'tg_username' => $data['tg_username']
                ]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function updatePassword($data, $user) {
        try {
            DB::beginTransaction();
            if (Hash::check($data['old_password'], $user->password)) {
                if ($data['new_password'] === $data['new_password_repeat']) {
                    $user->update([
                        'password' => Hash::make($data['new_password'])
                    ]);
                } else {
                    throw new \Exception('Пароли не совпадают!');
                }
            } else {
                throw new \Exception('Текущий пароль введен неверно!');
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

}
