<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        $roles = User::getRoles();
        unset($roles[-1]);
        return view('auth.login', compact('roles'));
    }

    protected function redirectTo()
    {
        if (auth()->user()->role_id == User::ROLE_EMPLOYEE) {
            return url('employee');
        }
        if ((auth()->user()->role_id == User::ROLE_STUDENT) ||
            (auth()->user()->role_id == User::ROLE_TEACHER)
        ) {
            return url('personal');
        }
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }
}
