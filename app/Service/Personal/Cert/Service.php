<?php


namespace App\Service\Personal\Cert;

use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            Student::create([
                'student_id_number' => $application->student_id_number,
                'group_id' => $application->group_id,
                'user_id' => $application->user_id,
            ]);

            $password = Str::random(10);
            $application->user->update([
                'password' => Hash::make($password),
            ]);
            Mail::to($application->user->email)->send(new PasswordMail($password));
            $application->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $news)
    {

    }
}
