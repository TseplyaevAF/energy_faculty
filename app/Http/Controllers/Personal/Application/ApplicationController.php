<?php

namespace App\Http\Controllers\Personal\Application;

use App\Http\Controllers\Controller;
use App\Mail\User\PasswordMail;
use App\Models\Student\Student;
use App\Models\Student\StudentApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index()
    {
        Gate::authorize('isHeadman');
        $applications = StudentApplication::all();
        return view('personal.application.index', compact('applications'));
    }

    public function accept(StudentApplication $application)
    {
        Gate::authorize('isHeadman');
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
        return redirect()->route('personal.application.index');
    }

    public function reject(StudentApplication $application)
    {
        Gate::authorize('isHeadman');
        return redirect()->route('personal.application.index');
    }
}
