<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class Authenticate extends Middleware
{

    // protected function redirectTo($request)
    // {
    //   if (!$request->expectsJson()) {
    //     return route('backend.login');
    //   }
    // }

  public function handle($request, Closure $next, ...$guards)
  {
    // dd($guards);
    $guards = empty($guards) ? [null] : $guards;
    foreach ($guards as $guard) {
      if (Auth::guard($guard)->check()) {
        Auth::shouldUse((string)$guard);
        return $next($request);
      }
      return redirect(RouteServiceProvider::LOGIN_PAGE);
    }
  }
}
