<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roleId)
    {
        if ($request->user()->role->id < $roleId) {
             if ($request->ajax()) {
                return response('Forbidden.', 403);
            } else {
                return redirect('/')->with([
                    'alert' => 'You are not authorized to do that.',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
        }
        
        return $next($request);
    }
}
