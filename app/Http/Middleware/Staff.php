<?php

namespace App\Http\Middleware;

use Closure;

class Staff
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

        if (!auth()->user()->hasGroup('sg.staff')) {
            //auth()->logout();
            //session()->invalidate();
            return redirect()->route('home')->with(['error' => 'You are not part of the staff group']);        
        }

        return $next($request);

    }

}
