<?php

namespace App\Http\Controllers\Apiaudiophonecontrollers;

use App\Apiaudiophonemodels\ApiAudiophoneUser;
//=== Devolver un usuario autenticado ===============//
//use Illuminate\Support\Facades\Auth;
//=====================================//
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use Dusterio\LumenPassport\Http\Controllers\AccessTokenController;
use App\Traits\IssueTokenAudiophoneApiTrait;


class LoginAudiophoneUserController extends AccessTokenController
{

    use IssueTokenAudiophoneApiTrait;

    /**
     * login ApiAudiophoneUser instance.
     *
     * @param ServerRequestInterface $request
     * @return mixed
    */
    public function loginApiaudiophoneUser(ServerRequestInterface $request)
    {

        //obtenemos valores del request de esta manera ya que es un objeto tipo ServerRequestInterface
        $grant_type = $request->getParsedBody()['grant_type'];
        $email = $request->getParsedBody()['username'];
        $password = $request->getParsedBody()['password'];

        //validamos que la peticion cumpla con los parámetros requeridos
        $valida_email = $this->validateEmail($email);
        $valida_string = $this->validateString($grant_type,  $password);

        if(($valida_email == true) && ($valida_string == true)){

            //buscamos usuario en la base de datos
            $apiaudiophoneuser = ApiAudiophoneUser::where('apiaudiophoneusers_email',$email)->first();

            //validamos que el hash del password sea igual al que esta en la BD y que el estatus del user sea true
            if(($apiaudiophoneuser) && (Hash::check($password, $apiaudiophoneuser->apiaudiophoneusers_password)) && ($apiaudiophoneuser->apiaudiophoneusers_status == true) && ($grant_type == 'password') && ($apiaudiophoneuser->apiaudiophoneusers_role == 'ADMIN_ROLE')){

              //Generamos el apiToken invocando la funcion del trait issueToken
              $tokenResponse = $this->issueToken($request);

                return response()->json([

                    'ok' => true,
                    'status' => 200,
                    'message' => 'Bienvenido a Apiaudiophone',
                    'apiaudiophoneusers_fullname' => $apiaudiophoneuser->apiaudiophoneusers_fullname,
                    'apiaudiophoneusers_role' => $apiaudiophoneuser->apiaudiophoneusers_role,
                    'apiaudiophoneusers_email' => $apiaudiophoneuser->apiaudiophoneusers_email,
                    'apiaudiophoneusers_id' => $apiaudiophoneuser->apiaudiophoneusers_id,
                    'apiToken' => $tokenResponse
                ]);
            }elseif(($apiaudiophoneuser) && (Hash::check($password, $apiaudiophoneuser->apiaudiophoneusers_password)) && ($apiaudiophoneuser->apiaudiophoneusers_status == false) && ($grant_type == 'password') && ($apiaudiophoneuser->apiaudiophoneusers_role == 'ADMIN_ROLE')){

                return response()->json([

                    'ok' => true,
                    'status' => 401,
                    'apiaudiophoneuser' => $apiaudiophoneuser->apiaudiophoneusers_fullname,
                    'message' => 'Usuario Inactivo'
                ], 401);
            }else{

                return response()->json([

                    'ok' => true,
                    'status' => 422,
                    'message' => 'Credenciales Inválidas, acceso único para Administradores.'
                ], 422);
            }
        }else{

            return response()->json([

                'ok' => true,
                'status' => 400,
                'message' => 'Error en los parámetros del request'
            ], 400);
        }
    }
    /**
     * Refresh token instance.
     *
     * @param ServerRequestInterface $request
     * @return mixed
    */
    public function refreshTokenApiaudiophoneUser(ServerRequestInterface $request)
    {

        //obtenemos valores del request de esta manera ya que es un objeto tipo ServerRequestInterface
        $grant_type = $request->getParsedBody()['grant_type'];
        $refresh_token = $request->getParsedBody()['refresh_token'];

        //Filtramos los parámetros correctos de la petición para el nuevo acces token
        if(($refresh_token) && ($grant_type == 'refresh_token')){

            //Generamos el nuevo apiToken partiendo del refresh_token
            $tokenRefresh = $this->issueToken($request);

            return response()->json([

                'ok' => true,
                'status' => 200,
                'apiToken' => $tokenRefresh
            ], 200);
        }else{

            return response()->json([

                'ok' => true,
                'status' => 405,
                'message' => 'Error en los parámetros del request'
            ], 405);
        }
    }
    /**
     * logoutApiAudiophoneUser instance.
     *
     * @return mixed
    */
    public function logoutApiaudiophoneUser(){

        // Auth::logout(); Se necesita un usuario autenticado para eliminar la sesion de esa instancia.
        //meter método dentro del middleware auth:api
        return response()->json([

            'ok' => true,
            'status' => 200,
            'message' => 'Sesión Finalizada'
        ], 200);
    }
    /**
     * Validate email ApiAudiophoneUser instance.
     *
     * @param String $email
     * @return boolean
    */
    public function validateEmail($email = null)
    {

        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            return true;
        }else{

            return false;
        }
    }
    /**
     * Validate grant_type & password ApiAudiophoneUser instance.
     *
     * @param String $grant_type
     * @param String $password
     * @return boolean
    */
    public function validateString($grant_type_par = null, $password_par = null)
    {

        if(ctype_alpha($grant_type_par) && is_string($password_par)){

            return true;
        }else{

            return false;
        }
    }
}

