<?php

namespace App\Http\Middleware;

use Closure, User;

class Sso
{
    /**
     * Handle Single Sign On
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($request->url) && isset($_SESSION['user']) && !$request->has('access_denied')) {
            $parsed = parse_url($request->url);

            $url = $parsed['scheme'] . '://' . $parsed['host'] . "/auth?key=" . base64_encode(implode('|', [
                date('Y-m-d H:i'),
                $_SESSION['user']['entity_id']
            ])) . "&redirect=" . $request->url;

            return redirect($url);
        }
        return $next($request);
    }
}
