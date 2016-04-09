<?php

namespace App\Http\Middleware;

use Closure;

class ResolveLocale
{
    /**
     *
     * @var array
     */
    protected $locales = [
      'hr' => 'Hrvatski',
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the request has non empty lang parameter.
        if ($request->has('locale')) {
            
            $locale = $request->get('locale');
            
            // Make sure we have it in our available locales.
            if (key_exists($locale, $this->locales)) {
                \App::setLocale($locale);
                // Add it to session, as array (for later merging in URLs).
                session()->put('locale', ['locale'=>$locale]);
                return $next($request);
            }
            else {
                // Make sure we don't open any other pages for invalid locale.
                abort(404);
            }
            
        }
        
        // Else set the default locale.
        \App::setLocale(config('app.locale'));
        // Add empty array to locale in session
        session()->put('locale', []);
        
        return $next($request);
        
        
    }
}
