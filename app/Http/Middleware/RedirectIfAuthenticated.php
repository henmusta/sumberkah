<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use  Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
  public function handle(Request $request, Closure $next, ...$guards)
  {
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
      switch ($guard) {
        case 'henmus':
          if (Auth::guard($guard)->check()) {
                $cek_online = Cache::has('is_online' . auth()->user()->id);
                if( $cek_online == 'true'){
                    return redirect(RouteServiceProvider::DASHBOARD_PAGE);
                }
          }
          break;

      }
    }

    return $next($request);
  }
}
