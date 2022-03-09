<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function redirectTo()
    {
        $role_id = auth()->user()->role_id;
        if (($role_id == User::ROLE_EMPLOYEE) ||
            ($role_id == User::ROLE_CA)
        ) {
            return url('employee');
        }
        if (($role_id == User::ROLE_STUDENT) ||
            ($role_id == User::ROLE_TEACHER)
        ) {
            return url('personal');
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            if (auth()->user()->is_active_2fa) {
                auth()->user()->generateCode();

                return redirect()->route('2fa.index');
            }
            else {
                return $this->redirectTo();
            }
        }

        return redirect()
            ->route('login')
            ->with('error', 'Данные введены неверно');
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }
}
