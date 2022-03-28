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

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    protected function redirectTo()
    {
        $role_id = auth()->user()->role_id;
        if (($role_id == User::ROLE_STUDENT) ||
            ($role_id == User::ROLE_TEACHER)
        ) {
            return redirect()->route('personal.main.index');
        }

        if ($role_id == User::ROLE_EMPLOYEE) {
            return redirect()->route('employee.main.index');
        }

        if ($role_id == User::ROLE_CA) {
            return redirect()->route('ca.main.index');
        }

        if ($role_id == User::ROLE_DEKANAT) {
            return redirect()->route('dekanat.main.index');
        }

        return redirect()->route('home');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            if (auth()->user()->is_active_2fa) {
                try {
                    auth()->user()->generateCode();
                } catch (\Exception $exception) {
                    $message = $exception->getMessage();
                    return view('error.error-info', compact('message'));
                }
                return redirect()->route('2fa.index');
            } else {
                return $this->redirectPath();
            }
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function loggedOut()
    {
        return redirect()->route('login');
    }
}
