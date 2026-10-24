<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        /*if ($_COOKIE['isAdminCookie'] !== 'jammAdmin') {
            return abort(404);
        }*/
        if (!is_null(request()->user())) {
            /*if (Auth::user()->stageRegister!==8){
                return redirect('/');
            }*/
            return $next($request);
        } else {
            return redirect('login');
        }
    }
}
