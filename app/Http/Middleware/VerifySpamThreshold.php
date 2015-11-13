<?php

namespace App\Http\Middleware;

use Closure;

class VerifySpamThreshold
{
    /**
     * Handle an incoming request. Check if the spam threshold for the current
     * suggested term/definition is being respected. User can only have a 
     * certain number of suggested/rejected terms or definitions, which is 
     * defined in user role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userId = $request->user()->id;
        $spamThreshold = $request->user()->role->spam_threshold;
        
        // First we have to get the current number of suggested/rejected 
        // (not approved)terms and definitions.
        $total = DB::table('terms')->select('terms.user_id', DB::raw('COUNT(term) + def_sum as total')  )
                ->leftJoin(DB::raw('(SELECT def.user_id, COUNT(def.definition) as def_sum'
                    . ' FROM definitions AS def'
                    . ' WHERE def.user_id = ? AND def.status_id < 1000'
                    . ' GROUP BY def.user_id) as def'), 'terms.user_id', '=', 'def.user_id')
                ->setBindings([$userId])
                ->where('terms.status_id','<', 1000)
                ->where('terms.user_id', $userId)
                ->groupBy('terms.user_id')
                ->value('total');
        
        
        if ($request->user()->role->spam_threshold < $total) {
             if ($request->ajax()) {
                return response('Forbidden.', 403);
            } else {
                return back()->with([
                    'alert' => 'You can only have up to ' . $spamThreshold 
                        . ' suggested (and rejected) terms and definitions.',
                    'alert_class' => 'alert alert-warning'
                ]);
            }
        }
        
        return $next($request);
    }
}
