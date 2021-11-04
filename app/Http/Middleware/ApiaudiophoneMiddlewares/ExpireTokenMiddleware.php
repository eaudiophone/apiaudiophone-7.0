<?php

namespace App\Http\Middleware\ApiaudiophoneMiddlewares;

use App\Apiaudiophonemodels\ApiAudiophoneUser;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Closure;

class ExpireTokenMiddleware
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

        $userData = Auth::user();

        $hora_actual = Carbon::now()->format('H:i:s');

        $expires_at = $userData->oauth_acces_token->max('expires_at'); 

        $array_hora_expiracion = explode(' ', $expires_at); 

        $hora_expiracion = $array_hora_expiracion[1];


        if($hora_actual > $hora_expiracion){            

            return response()->json([

                'error' => true,
                'status' => 401,
                'message' => 'token expired'
            ], 401);            
        }

        return $next($request);
    }
}
