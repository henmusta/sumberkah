<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Cache;
use Carbon\Carbon;

class LastSeenUserActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $expireTime = Carbon::now()->addMinutes(2); // keep online for 2 min
            Cache::put('is_online'.Auth::user()->id, true, $expireTime);
            //Last Seen
            User::where('id', Auth::user()->id)->update(['last_seen' => Carbon::now()]);
        }
        return $next($request);
    }
}
