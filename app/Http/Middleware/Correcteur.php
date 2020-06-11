<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\RepartitionController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class Correcteur
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
        // Get path param ue_id
        $ue_id = $request->route('ue_id');
        $correction_id = $request->route('corr_id');
        if ($correction_id != null && !RepartitionController::haveCorrection($correction_id,Auth::user()->id)) {
            return redirect(RouteServiceProvider::HOME);
        }
        
        if (RepartitionController::isCorrecteur(Auth::user()->id,$ue_id)){
            return $next($request);
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
