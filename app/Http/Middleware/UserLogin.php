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
        if (User::loggedIn()) {
            // TODO: queue
            SessionService::action();
            return $next($request);
        }
        return response(null, 401);
        // if ($request->isMethod('get')) {
        //     return
        // }
    }
}
