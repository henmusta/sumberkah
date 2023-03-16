<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use Shetabit\Visitor\Traits\Visitor;
use App\Providers\RouteServiceProvider;
use App\Traits\RedirectsUsers;
use App\Models\User;
use App\Traits\ThrottlesLogins;
use App\Traits\ValidateLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Cache;

class LoginController extends Controller
{

  use ValidateLogin, RedirectsUsers, ThrottlesLogins;

  public function __construct()
  {
    $this->middleware('guest:henmus')->except('logout');
  }

  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $this->validateLogin($request);
    if (method_exists($this, 'hasTooManyLoginAttempts') &&
      $this->hasTooManyLoginAttempts($request)) {
      $this->fireLockoutEvent($request);
      $this->sendLockoutResponse($request);
    }

    $remember = $request->has('remember');

    $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';


   // dd(Cache::has('is_online' . auth()->user()->id));

    $data = [
       $fieldType => $request['email'],
      'password' => $request['password']
    ];

    $has_pass =  Hash::make($request['password']);
    // dd($has_pass);
    $cek_user =  User::where( $fieldType , $request['email'])->first();
    $cek_online = null;
    if(isset($cek_user)){
        $cek_online = Cache::has('is_online' .  $cek_user['id']);
    }

    // dd($cek_user);
    // visitor()->isOnline($user); // determines if the given user is online
    // $user->isOnline(); // another way
    // dd($cek_online);
    if( $cek_online == 'true'){
        $response = response()->json([
            'status' => 'error',
            'message' => 'Anda Sedang Login Di Device Lain, Sialakan Logout Terlebih Dahulu atau Tunggu 1 Menit Lagi',
            'redirect' => 'reload'
        ]);
    }else{
        if (Auth::attempt($data, $remember)) {
            $response = response()->json([
                'status' => 'success',
                'message' => 'Berhasil Login',
                'redirect' => RouteServiceProvider::DASHBOARD_PAGE
            ]);
        } else {
          $this->incrementLoginAttempts($request);
          $this->sendFailedLoginResponse($request);

          return redirect(RouteServiceProvider::LOGIN_PAGE);
        }

    }
    return $response;




  }

  public function logout(Request $request)
  {
    // print_r(guard);
    // dd(Auth::user()->id);
    if(isset(Auth::user()->id)){
        Cache::forget('is_online' .  Auth::user()->id);
    }
    if ($this->guard('henmus')->check()) {
      $redirect = redirect(RouteServiceProvider::LOGIN_PAGE);
    }else{
      $redirect = redirect(RouteServiceProvider::LOGIN_PAGE);
    }
    $this->guard()->logout();
    $request->session()->invalidate();
    $request->session()->regenerate();
    return $redirect;
  }


}
