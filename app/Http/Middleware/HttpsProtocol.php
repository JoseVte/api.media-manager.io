<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\ResponseFactory;

class HttpsProtocol
{
    /**
     * @return Response|ResponseFactory|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->secure() && (app()->environment() === 'production' || env('REDIRECT_HTTPS'))) {
            return redirect()->to($request->getRequestUri(), 302, [], true);
        }

        return $next($request);
    }
}
