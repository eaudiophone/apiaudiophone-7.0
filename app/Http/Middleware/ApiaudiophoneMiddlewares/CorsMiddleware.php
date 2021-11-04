<?php

namespace App\Http\Middleware\ApiaudiophoneMiddlewares;

use Closure;

class CorsMiddleware
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

        /*
        * Adecuamos el middleware para q cuando llegue el request a la aplicación sea
        * seteado, permitir headers desde otro server y responder con el token solicitado.
        */

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type:application/json, X-Token-Auth, Authorization, Accept');

        return $response;

    }
}