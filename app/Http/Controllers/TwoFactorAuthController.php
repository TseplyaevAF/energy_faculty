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

            return redirect()->route('home');
        }

        return redirect()
            ->back()
            ->with('error', 'Вы ввели не правильный код.');
    }
    /**
     * resend otp code
     *
     */
    public function resend()
    {
        auth()->user()->generateCode();

        return back()
            ->with('success', 'На Ваш номер телефона было отправлено СМС с кодом для входа.');
    }
}
