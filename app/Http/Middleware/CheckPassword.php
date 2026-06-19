<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->role === 'user') {
            if ($user && ($user->default_password || is_null($user->pin))) {

                // Hindari infinite loop: Izinkan akses JIKA sedang di halaman setup atau logout
                if (!$request->routeIs('user.setup.*') && !$request->routeIs('logout')) {
                    return redirect()->route('user.setup.index');
                }
            }

            // Sebaliknya, jika sudah setup tapi mencoba mengakses halaman setup lagi, lempar ke dashboard
            if ($user && $user->default_password && !is_null($user->pin) && $request->routeIs('user.setup.*')) {
                return redirect()->route('dashboard');
            }
        }
        return $next($request);
    }
}
