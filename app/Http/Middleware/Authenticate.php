<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $url = $request->url();
        if (strpos($url,'company') !== false) {
            return route('login');
        }
        if (strpos($url,'personnel') !== false) {
            return route('personnel/login');
        }
        if (strpos($url,'superadmin') !== false) {
            return route('superadmin');
        }
        if(strpos($url, 'api') !== false) {
        //dd($request->all());
        // if (! $request->expectsJson()) {
        //     //return route('login');
        //     //return url('/');
        //    return route('loginerror');
        // } else {
          if (! $request->expectsJson()) {
            //return route('login');
            //return url('/');
           return route('loginerror');
            }      
        //}
        }
    }
}
