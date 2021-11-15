<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function auth(Request $request)
	{
		$arr = $request->all();
		$pass = bcrypt( $arr['password'] );
		$arr['password'] =  $pass;
		$arr['password_confirmation'] = $pass;
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password],true)) {
            $user = User::where('email', '=', $request->email )->select('name')->first();
            return redirect('user/' . $user->name);
        } else {
            return redirect()->back()->withInput()->with( array('message'=>'Введите существующий mail и пароль для входа'));
        }
	}
}
