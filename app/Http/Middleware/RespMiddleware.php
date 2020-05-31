<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Providers\RouteServiceProvider;
class RespMiddleware
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
        if (UserController::isResponsable(Auth::user(),$request->route('id')))
            return $next($request);
        return redirect(RouteServiceProvider::HOME);
    }
}
