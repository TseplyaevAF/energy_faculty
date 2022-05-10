<?php

namespace App\Http\Controllers;

use App\Models\UserCode;
use Illuminate\Http\Request;

class TwoFactorAuthController extends Controller
{
    /**
     * index method for 2fa
     *
     */
    public function index()
    {
        return view('auth.2fa');
    }

    /**
     * validate sms
     *
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required',
        ]);

        $userCode = UserCode::where('user_id', auth()->user()->id)
            ->where('code', $validated['code'])
            ->where('updated_at', '>=', now()->subMinutes(5));

        if ($userCode->exists()) {
            session(['2fa' => auth()->user()->id]);
            $userCode->delete();

            return redirect()->route('login');
        }

        return redirect()
            ->back()
            ->with('error', 'Вы ввели неправильный код.');
    }
    /**
     * resend otp code
     *
     */
    public function resend()
    {
        try {
            auth()->user()->generateCode();
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            return view('error.error-info', compact('message'));
        }

        return back()
            ->with('success', 'Дождитесь звонка на Ваш номер.');
    }
}
