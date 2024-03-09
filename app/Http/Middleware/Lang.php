<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Lang
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
        if (Auth::check()) {

            $livewireLocale = Auth::user()->lang;

            if ($livewireLocale) {
                if (in_array($livewireLocale, config('app.available_locales'))) {
                    app()->setLocale($livewireLocale);
                    session()->put('locale', $livewireLocale);
                }
            }
        }
        return $next($request);
    }
}
