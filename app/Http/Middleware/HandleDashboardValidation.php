<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleDashboardValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if ($this->is404($request)) {
                return response()->view('errors.404', [], 404);
            }
        } else {
            if ($this->is404($request)) {
                return response()->view('errors.404', [], 404);
            }
        }

        return $next($request);
    }

    private function is404(Request $request)
    {
        return $request->route()->named('dashboard');
    }
}
