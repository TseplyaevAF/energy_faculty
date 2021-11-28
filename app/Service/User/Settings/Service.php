<?php


namespace App\Service\User\Settings;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class Service
{
    public function update($data, $user) {
        try {
            DB::beginTransaction();
            if (isset($data['avatar'])) {
                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
                $user->getMedia('avatar')->count();
                $data['avatar'] = $user->getFirstMediaUrl('avatar');
            }
            $user->update($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }
        
}
