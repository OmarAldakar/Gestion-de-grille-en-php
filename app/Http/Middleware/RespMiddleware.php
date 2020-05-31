<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Providers\RouteServiceProvider;
use App\UE;
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
        // Get path ue id
        $ue_id = $request->route('id');

        // If is responsable continue
        if (UserController::isResponsable(Auth::user(),$ue_id)){
            $ex_id = $request->route('ex_id');
            //if $ex_id is not defined or exercice i is not associated with ue j  
            if ($ex_id == null || UE::find($ue_id)->exercices()->find($ex_id) != null) {
                return $next($request);
            }
            return redirect(RouteServiceProvider::HOME);
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
