<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
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
        $roles = User::getRoles();
        unset($roles[-1]);
        return view('auth.login', compact('roles'));
    }

    // public function username()
    // {
    //     $login = request()->input('email');
    //     $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    //     request()->merge([$field => $login]);
    //     return $field;
    // }

    public function authenticated(Request $request)
    {
        // if (Auth::attempt([
        //     'email' => $request->email,
        //     'password' => $request->password,
        //     'role_id' => $request->role_id
        // ])) {
        //     return redirect()->intended('employee');
        // }
    }

    // public function attemptLogin(Request $request) {
    //     $credentials = $request->only('email', 'password', 'role_id');
    //     if (Auth::attempt($credentials)) {
    //         if ($request->role_id == 3) {
    //             dd(Role::where('employee_id', '!=', null));
    //         }
    //         return User::where('email', '=', $request->email )->select('name')->first();
    //     }
    // }

    protected function redirectTo()
    {
        if (isset(auth()->user()->role->employee_id)) {
            return url('employee');
        } else {
            return url('home');
        }
    }

    protected function loggedOut(Request $request) {
        return redirect()->route('login');
    }
}
