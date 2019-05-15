<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Utils\SessionService;

class UserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        if (User::loggedIn()) {
            // TODO: queue
            SessionService::action();
            return $next($request);
        }
        return response(null, 401);
    }
}
