<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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

        //header('Access-Control-Allow-Origin:  http://localhost:4200');
        $response = $next($request);
        $headers = [
            'Cache-Control' => 'nocache, no-store, max-age=0, must-revalidate',
            'Pragma', 'no-cache',
            'Expires', 'Fri, 01 Jan 1990 00:00:00 GMT',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers'=> 'X-Requested-With,Content-Type, X-Auth-Token, Authorization, Origin',
            'Access-Control-Allow-Methods'=> 'POST, PUT, GET,DELETE,OPTIONS',
            

        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        return $response;
        /* return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Headers', 'X-Requested-With,Content-Type, X-Auth-Token, Authorization, Origin')
            ->header('Access-Control-Allow-Methods', 'POST, PUT, GET,DELETE,OPTIONS'); */
    }
}
