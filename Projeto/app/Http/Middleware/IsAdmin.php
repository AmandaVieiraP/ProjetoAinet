<?php

namespace App\Http\Middleware;
use
\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Closure;

class IsAdmin
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
        if ($request->user() && $request->user()->admin == 1) {
            return $next($request);
        }
        return redirect()->route('initial.page')->with('msg', "Not autorized to see the users page");
    }
}
