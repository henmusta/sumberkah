<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ThrottlesLogins;
use App\Traits\ValidateLogin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Api_LoginController extends Controller
{
    use ValidateLogin, ThrottlesLogins;
    public function index()
    {
        $data = User::all();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data, true);
        } else {
            return ApiFormatter::createApi(200, 'Failed');
        }
    }

    public function login(Request $request)
    {
    //dd($request->email);
      $this->validateLogin($request);
      if (method_exists($this, 'hasTooManyLoginAttempts') &&
        $this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);
        $this->sendLockoutResponse($request);
      }

      $remember = $request->has('remember');

      $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
      $data = [
        $fieldType => $request['email'],
        'password' => $request['password']
      ];
      if (Auth::attempt($data, $remember)) {
        return ApiFormatter::createApi(200, 'Success', Auth::user(), true);
      } else {
        return ApiFormatter::createApi(200, 'Periksa Email Atau Sandi!', null, false);
      }
    }

    public function logout(Request $request)
    {
        return ApiFormatter::createApi(200, 'Success');
    }

}
