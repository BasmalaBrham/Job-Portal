<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
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
        // Check if user is authenticated
        if (! $request->user()) {
            session()->flash('error', 'You are not authorized to access this page');
            return redirect()->route('login');
        }

        // Check if user is admin
        if ($request->user()->role != 'admin') {
            session()->flash('error', 'You are not authorized to access this page');
            return redirect()->route('account.profile');
        }
        return $next($request);
    }
}
