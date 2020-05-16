<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Providers\RouteServiceProvider;


class AdminMiddleware
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
        if (UserController::isAdmin(Auth::user())) {
            return $next($request);
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
