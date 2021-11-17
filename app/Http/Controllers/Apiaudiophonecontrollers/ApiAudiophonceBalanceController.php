<?php

namespace App\Http\Controllers\ApiAudiophonecontrollers;

use App\Apiaudiophonemodels\ApiAudiophoneUser;
use App\Apiaudiophonemodels\ApiAudiophoneClient;
use App\Apiaudiophonemodels\ApiAudiophoneBalance;
use App\Traits\ApiResponserTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class ApiAudiophonceBalanceController extends Controller
{

    use ApiResponserTrait;

    /**
     * show ApiAudiophoneBalance Instance
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function showApiaudiophoneBalance(Request $request, $id_apiaudiophoneusers = null){

       // :::: Validación del Request :::: //

        $this->validate($request, [

            'id_apiaudiophoneclients' => 'numeric|required',
            'stringsearch' => 'string|min:0|max:60',
            'start' => 'numeric'
        ]);

       // :::: Obtenemos los valores del request :::: //

        $balance_data_show = $request->all();

        // :::: Obtenemos el numero de parámetros del arreglo :::: //

        $parameters_total = count($balance_data_show);

        // :::: Obtenemos el id del cliente :::: //

        $id_client_request = $balance_data_show['id_apiaudiophoneclients'];

        // :::: Obtenemos las keys del arreglo de parámetros de entrada :::: //

        $keys_balance_data_show = $this->arrayKeysRequest($balance_data_show);

        // :::: Obtenemos la cantidad de registros contables general :::: //

        $bdbalancetotal = ApiAudiophoneBalance::count();

        // :::: Obtenemos la cantidad de registros contables por cliente:::: //

        $count_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)->count();

        // :::: Obtenemos el rol del usuario :::: //

        $user = ApiAudiophoneUser::userbalance($id_apiaudiophoneusers)->first();

        $user_role = $user->apiaudiophoneusers_role;

        // :::: asignamos la cantidad de registros por pagina :::: //

        $num_pag = 15;


        if($user_role == 'ADMIN_ROLE'){


           switch($count_balance_client)
           {
                // :::: Cuando no existen registros contables para ese cliente :::: //
                case 0:

                  return $this->errorResponse('No existen registros contables para el cliente', 404);
                break;
                default:

                // :::: Cuando es la primera consulta, solo viene el id del cliente :::: //
                if(($parameters_total == 1) && ($keys_balance_data_show[0] == 'id_apiaudiophoneclients')){

                    $balance_results = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)
                    ->skip(0)->take($num_pag)
                    ->orderBy('apiaudiophonebalances_id', 'desc')
                    ->get();


                    return $this->successResponseApiaudiophoneBalanceShow(true, 200, $bdbalancetotal, $count_balance_client, $balance_results);

                // :::: Cuando se hace búsqueda por stringsearch, id cliente requerido :::: //
                }elseif(($parameters_total == 2) && ($keys_balance_data_show[0] == 'id_apiaudiophoneclients') && ($keys_balance_data_show[1] == 'stringsearch')){

                    // :::: Obtenemos valor de la cadena :::: //
                    $chain = $balance_data_show['stringsearch'];

                    // :::: Cuando hay cadena con o sin espacio, primera búsqueda con stringsearch :::: //
                    if(((ctype_space($chain) == true) && ($chain)) || ((ctype_space($chain) == false) && ($chain))){


                        $balance_results = ApiAudiophoneBalance::where(['id_apiaudiophoneclients', $id_client_request], ['apiaudiophonebalances_desc', 'like', '%'.$chain.'%'])
                        ->orWhere(['id_apiaudiophoneclients', $id_client_request], ['apiaudiophonebalances_date', 'like', '%'.$chain.'%'])
                        ->skip(0)->take($num_pag)
                        ->orderBy('apiaudiophonebalances_id', 'desc')
                        ->get();

                        $balance_results_count = count($balance_results);

                        return $this->successResponseApiaudiophoneBalanceCount(true, 200, $bdbalancetotal, $balance_results_count, $balance_results);
                    // :::: Cuando no hay cadena:::: //
                    }else{

                        $balance_results = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)
                        ->skip(0)->take($num_pag)
                        ->orderBy('apiaudiophonebalances_id', 'desc')
                        ->get();

                        return $this->successResponseApiaudiophoneBalanceShow(true, 200, $bdbalancetotal, $balance_results);
                    }
                // :::: Cuando se hace búsqueda por paginación :::: //
                }elseif(($parameters_total == 2) && ($keys_balance_data_show[0] == 'id_apiaudiophoneclients') && ($keys_balance_data_show[1] == 'start')){

                    // :::: Obtenemos valor del start :::: //
                    $start = $balance_data_show['start'] - 1;

                    // :::: Cuando no hay parámetro start :::: //
                    if(!($start)){

                        $balance_results = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)
                        ->skip(0)->take($num_pag)
                        ->orderBy('apiaudiophonebalances_id', 'desc')
                        ->get();

                        return $this->successResponseApiaudiophoneBalanceShow(true, 200, $bdbalancetotal, $count_balance_client, $balance_results);
                    // :::: Cuando hay parámetro start :::: //
                    }else{

                        $balance_results = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)
                        ->skip($start)->take($num_pag)
                        ->orderBy('apiaudiophonebalances_id', 'desc')
                        ->get();

                        return $this->successResponseApiaudiophoneBalanceShow(true, 200, $bdbalancetotal, $count_balance_client, $balance_results);
                    }
                // :::: Cuando se hace búsqueda por stringsarch y por parámetro de búsqueda :::: //
                }elseif(($parameters_total == 3) && ($keys_balance_data_show[0] == 'id_apiaudiophoneclients') && ($keys_balance_data_show[1] == 'stringsearch') && ($keys_balance_data_show[1] == 'start')){


                    // :::: Obtenemos valor del request :::: //
                    $chain = $balance_data_show['stringsearch'];
                    $start = $balance_data_show['start'] - 1;

                    // :::: Cuando hay cadena con o sin espacio sin parámetro start de inicio :::: //
                    if(((ctype_space($chain) == true) && !($start)) || ((ctype_space($chain) == false) && !($start))){


                        $balance_results = ApiAudiophoneBalance::where(['id_apiaudiophoneclients', $id_client_request], ['apiaudiophonebalances_desc', 'like', '%'.$chain.'%'])
                        ->orWhere(['id_apiaudiophoneclients', $id_client_request], ['apiaudiophonebalances_date', 'like', '%'.$chain.'%'])
                        ->skip(0)->take($num_pag)
                        ->orderBy('apiaudiophonebalances_id', 'desc')
                        ->get();

                        $balance_results_count = count($balance_results);

                        return $this->successResponseApiaudiophoneBalanceCount(true, 200, $bdbalancetotal, $balance_results_count, $balance_results);

                    // :::: Cuando hay cadena con o sin espacio con parámetro start de inicio :::: //
                    }elseif(((ctype_space($chain) == true) && ($start)) || ((ctype_space($chain) == false) && ($start))){


                        $balance_results = ApiAudiophoneBalance::where(['id_apiaudiophoneclients', $id_client_request], ['apiaudiophonebalances_desc', 'like', '%'.$chain.'%'])
                        ->orWhere(['id_apiaudiophoneclients', $id_client_request], ['apiaudiophonebalances_date', 'like', '%'.$chain.'%'])
                        ->skip($start)->take($num_pag)
                        ->orderBy('apiaudiophonebalances_id', 'desc')
                        ->get();

                        $balance_results_count = count($balance_results);

                        return $this->successResponseApiaudiophoneBalanceCount(true, 200, $bdbalancetotal, $balance_results_count, $balance_results);

                    // :::: Cuando hay el stringsearch y el start están vacíos :::: //
                    }else{

                        $balance_results = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)
                        ->skip(0)->take($num_pag)
                        ->orderBy('apiaudiophonebalances_id', 'desc')
                        ->get();

                        return $this->successResponseApiaudiophoneBalanceShow(true, 200, $bdbalancetotal, $count_balance_client, $balance_results);
                    }
                // :::: si no vienen parámetros de consulta adicionales al ID del cliente :::: //
                }else{

                    $balance_results = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)
                    ->skip(0)->take($num_pag)
                    ->orderBy('apiaudiophonebalances_id', 'desc')
                    ->get();

                    return $this->successResponseApiaudiophoneBalanceShow(true, 200, $bdbalancetotal, $count_balance_client, $balance_results);
                }
            }
        }else{

            return $this->errorResponse('Usuario no autorizado para consultar registros contables', 401);
        }
    }

    /**
     * create ApiAudiophoneBalance Instance
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function createApiaudiophoneBalance(Request $request, $id_apiaudiophoneusers = null){

        // :::: Validación del Request :::: //

        $this->validate($request, [

            'id_apiaudiophoneclients' => 'numeric|required'
        ]);

        // :::: Obtenemos el ID del cliente :::: //

        $id_client_request = $request['id_apiaudiophoneclients'];

        // :::: Obtenemos la cantidad de registros contables general :::: //

        $bdbalancetotal = ApiAudiophoneBalance::count();

        // :::: Obtenemos la cantidad de registros de balance por usuario :::: //

        $count_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)->count();

        // :::: Obtenemos el rol del usuario :::: //

        $user = ApiAudiophoneUser::userbalance($id_apiaudiophoneusers)->first();

        $user_role = $user->apiaudiophoneusers_role;

        // ::: Asignamos el numero de páginas a consultar :::: //

        $num_pag = 5;


        // :::: GESTIONAMOS LA CONSULTA CORRESPONDIENTE :::: //

        if($user_role == 'ADMIN_ROLE'){

            switch($count_balance_client){

                case 0:

                    return $this->errorResponse('No existen registros contables para el cliente', 404);
                break;

                default:

                // :::: Consultamos registros creados para ese cliente :::: //

                $data_balance_create = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)
                ->skip(0)->take($num_pag)
                ->get();

                $data_balance_create_count = count($data_balance_create);

                return $this->successResponseApiaudiophoneBalanceCreate(true, 200, $bdbalancetotal, $data_balance_create_count, $data_balance_create);
            }
        }else{

            return $this->errorResponse('Usuario no autorizado para consultar Clientes', 401);
        }
    }


    /**
     * store ApiAudiophoneBalance Instance
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function storeApiaudiophoneBalance(Request $request, $id_apiaudiophoneusers = null){

        // :::: Validación del Request :::: //

        $this->validate($request, [

            'id_apiaudiophoneclients' => 'required|numeric',
            'apiaudiophonebalances_date' => 'string|min:0|max:60',
            'apiaudiophonebalances_desc' => 'required|string|min:0|max:60',
            'apiaudiophonebalances_horlab' => 'required|numeric',
            'apiaudiophonebalances_tarif' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'apiaudiophonebalances_debe' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
            'apiaudiophonebalances_haber' => 'numeric|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        // :::: Obtenemos los datos provenientes del request :::: //

        $balance_data_store = $request->all();

        // :::: Obtenemos el id del cliente :::: //

        $id_client_request = $balance_data_store['id_apiaudiophoneclients'];

        // :::: Obtenemos el rol de usuario :::: //

        $user = ApiAudiophoneUser::userbalance($id_apiaudiophoneusers)->first();

        $user_role = $user->apiaudiophoneusers_role;

        // :::: Obtenemos la cantidad de registros contables por cliente:::: //

        $count_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)->count();        

        // :::: Procedemos a actualizar el cliente :::: //

        switch($user_role){


            case('USER_ROLE'):

                return $this->errorResponse('Usuario no autorizado para crear Balances', 401);
            break;

            case('ADMIN_ROLE'):


                $apiaudiophonebalancenew = new ApiAudiophoneBalance;

                $apiaudiophonebalancenew->id_apiaudiophoneusers = $id_apiaudiophoneusers;
                $apiaudiophonebalancenew->id_apiaudiophoneclients = $id_client_request;
                $apiaudiophonebalancenew->apiaudiophonebalances_date = $balance_data_store['apiaudiophonebalances_date'];
                $apiaudiophonebalancenew->apiaudiophonebalances_desc = $balance_data_store['apiaudiophonebalances_desc'];
                $apiaudiophonebalancenew->apiaudiophonebalances_horlab = $balance_data_store['apiaudiophonebalances_horlab'];
                $apiaudiophonebalancenew->apiaudiophonebalances_tarif = $balance_data_store['apiaudiophonebalances_tarif'];
                $apiaudiophonebalancenew->apiaudiophonebalances_debe = $balance_data_store['apiaudiophonebalances_debe'];
                $apiaudiophonebalancenew->apiaudiophonebalances_haber = $balance_data_store['apiaudiophonebalances_haber'];


                if($count_balance_client == 0){


                    if($apiaudiophonebalancenew->apiaudiophonebalances_haber == 0){

                    
                        $apiaudiophonebalancenew->apiaudiophonebalances_total = 0 + $apiaudiophonebalancenew->apiaudiophonebalances_debe;
                    }elseif($apiaudiophonebalancenew->apiaudiophonebalances_debe == 0){
                       
                       $apiaudiophonebalancenew->apiaudiophonebalances_total = 0 - $apiaudiophonebalancenew->apiaudiophonebalances_haber;
                    }else{

                        return $this->errorResponse('Metodo no Permitido', 405);
                    }
                }else{

                    // :::: obtenemos el ultimo registro de balance de ese cliente :::: //
                    
                    $last_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $id_client_request)->get()->last();

                    $total = $last_balance_client['apiaudiophonebalances_total'];
                    

                    if($apiaudiophonebalancenew->apiaudiophonebalances_haber == 0){

                    
                        $apiaudiophonebalancenew->apiaudiophonebalances_total = $total + $apiaudiophonebalancenew->apiaudiophonebalances_debe; 
                    }elseif($apiaudiophonebalancenew->apiaudiophonebalances_debe == 0){
                       
                       $apiaudiophonebalancenew->apiaudiophonebalances_total = $total - $apiaudiophonebalancenew->apiaudiophonebalances_haber;
                    }else{

                        return $this->errorResponse('Metodo no Permitido', 405);
                    }
                }

                $apiaudiophonebalancenew->save();

                return $this->successResponseApiaudiophoneBalanceStore(true, 201, 'Balance creado Satisfactoriamente', $apiaudiophonebalancenew);
            break;

            default:

            return $this->errorResponse('Metodo no Permitido', 405);
        }
    }


    /**
     * update ApiAudiophoneBalance Instance
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function updateApiaudiophoneBalance(Request $request, $id_apiaudiophoneusers = null){

        // :::: Validación del Request :::: //

        $this->validate($request, [

            'id_apiaudiophoneclients' => 'required|numeric',
            'apiaudiophonebalances_id' => 'required|numeric',
            'apiaudiophonebalances_date' => 'string|min:0|max:60',
            'apiaudiophonebalances_desc' => 'required|string|min:0|max:60',
            'apiaudiophonebalances_horlab' => 'required|numeric',
            'apiaudiophonebalances_tarif' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'apiaudiophonebalances_debe' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
            'apiaudiophonebalances_haber' => 'numeric|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        // :::: Obtenemos los datos provenientes del request :::: //

        $balance_data_update = $request->all();

        // :::: Obtenemos el ID del balance a actualizar :::: //

        $balance_id_update = $request['apiaudiophonebalances_id'];

        // :::: Obtenemos el id del cliente a actualizar :::: //

        $balance_id_client_update = $request['id_apiaudiophoneclients'];

        // :::: Obtenemos el rol de usuario :::: //

        $user = ApiAudiophoneUser::userbalance($id_apiaudiophoneusers)->first();

        $user_role = $user->apiaudiophoneusers_role;

        // :::: Obtenemos datos necesarios del ultimo registro de ese cliente :::: //

        $last_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $balance_id_client_update)->get()->last();

        $id_balance_last = $last_balance_client['apiaudiophonebalances_id'];
        
        // :::: Obtenemos el numero total de registros contables para ese cliente :::: //    

        $count_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $balance_id_client_update)->count();
        
        // :::: Obtenemos el conteo de registros a partir del actualizado :::: //

        $count_balance_update = ApiAudiophoneBalance::where([
            ['id_apiaudiophoneclients', $balance_id_client_update], 
            ['apiaudiophonebalances_id', '>=', $balance_id_update]
        ])->count(); 

        // :::: Obtenemos los datos necesarios del primer balance para ese cliente :::: //

        $first_balance_client = ApiAudiophoneBalance::where([
            ['id_apiaudiophoneclients', $balance_id_client_update], 
            ['apiaudiophonebalances_id', '>=', $balance_id_update]
        ])->take(1)->orderBy('apiaudiophonebalances_id', 'asc')->get();

        $id_balance_first = $first_balance_client[0]['apiaudiophonebalances_id'];

        // :::: Procedemos a actualizar el balance :::: //

        switch($user_role){


            case('USER_ROLE'):

                return $this->errorResponse('Usuario no autorizado para actualizar Balances', 401);
            break;

            case('ADMIN_ROLE'):

                // :::: Cuando es el primer registro a actualizar :::: //
                if( ($balance_id_update == $id_balance_first) && ( $count_balance_client == $count_balance_update) ){

                   // dd('Cuando es el primer registro a actualizar');

                    // :::: Obtenemos el conteo de registros a partir del actualizado :::: //

                    $update_balance_count = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $balance_id_client_update], 
                        ['apiaudiophonebalances_id', '>=', $balance_id_update]
                    ])->count();

                   // dd($update_balance_count);

                    // ::: Obtenemos los registros involucrados en el update :::: //

                    $update_balance_data = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $balance_id_client_update], 
                        ['apiaudiophonebalances_id', '>=', $balance_id_update]
                    ])->get();

                    
                    // :::: Ciclo de actualización :::: //

                    for($i = 0; $i < $update_balance_count; $i++){

                        if($i == 0){

                            // :::: Obtenemos el registro a actualizar en la base de datos, los mismos se llenan del request :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($balance_id_update);

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $balance_data_update['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $balance_data_update['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $balance_data_update['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $balance_data_update['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $balance_data_update['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $balance_data_update['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $balance_data_update['apiaudiophonebalances_haber'];

                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = 0 + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = 0 - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;                               
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }
                            
                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                              
                        }else{

                            // :::: Arreglo Anterior :::: //

                            $arreglo_previo = $i - 1;                         

                            // :::: Obtenemos el id del registro actualizado en la iteración anterior :::: //

                            $id_balance_previo_actualizado = $update_balance_data[$arreglo_previo]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro actualizado en la iteración anterior:::: //

                            $preaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_previo_actualizado);

                            // :::: Obtenemos el total del registro actualizado en la iteración anterior :::: //

                            $pretotal = $preaudiophonebalanceupdate->apiaudiophonebalances_total;

                            // :::: Obtenemos el Id del registro a actualizar posteriores a la primera iteración :::: //

                            $id_balance_actualiza = $update_balance_data[$i]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro a actualizar :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_actualiza);

                            // :::: Procedemos a actualizar el registro :::: //

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $update_balance_data[$i]['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $update_balance_data[$i]['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $update_balance_data[$i]['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $update_balance_data[$i]['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $update_balance_data[$i]['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $update_balance_data[$i]['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $update_balance_data[$i]['apiaudiophonebalances_haber'];


                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;      
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }

                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                          
                        }
                    }

                    return $this->successResponseApiaudiophoneBalanceUpdateDos(true, 200, 'Balance actualizado Satisfactoriamente');


                    // :::: Cuando es el ultimo registro que se va actualizar :::: //
                }elseif( $balance_id_update == $id_balance_last ){

                   // dd('Cuando es el ultimo registro que se va actualizar');
                    
                    // :::: Obtenemos el penultimo balance de ese cliente para obtener el total :::: //

                    $prelast_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients',  $balance_id_client_update)->take(2)->orderBy('apiaudiophonebalances_id', 'desc')->get();

                    $pretotal_last = $prelast_balance_client[1]['apiaudiophonebalances_total'];

  
                    // :::: Realizamos el proceso de actualización :::: //

                    $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($balance_id_update);

                    $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                    $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $balance_data_update['id_apiaudiophoneclients'];
                    $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $balance_data_update['apiaudiophonebalances_date'];
                    $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $balance_data_update['apiaudiophonebalances_desc'];
                    $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $balance_data_update['apiaudiophonebalances_horlab'];
                    $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $balance_data_update['apiaudiophonebalances_tarif'];
                    $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $balance_data_update['apiaudiophonebalances_debe'];
                    $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $balance_data_update['apiaudiophonebalances_haber'];
                    

                    // :::: Lógica para el llenado del total :::: //

                    if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                    
                        $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal_last + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe; 
                    }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                       
                        $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal_last - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;
                    }else{

                        return $this->errorResponse('Metodo no Permitido', 405);
                    }

                    
                    // :::: Actualizamos Registro :::: //

                    $apiaudiophonebalanceupdate->update();

                    //return $this->successResponseApiaudiophoneBalanceUpdate(true, 200, 'Balance actualizado Satisfactoriamente', $apiaudiophonebalanceupdate);

                    return $this->successResponseApiaudiophoneBalanceUpdateDos(true, 200, 'Balance actualizado Satisfactoriamente');
                
                    // :::: Cuando es un registro intermedio para actualizar :::: //
                }else{

                   // dd('Cuando es un registro intermedio para actualizar');
                    
                    // :::: Obtenemos el conteo de registros a partir del actualizado :::: //

                    $update_balance_count = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $balance_id_client_update], 
                        ['apiaudiophonebalances_id', '>=', $balance_id_update]
                    ])->count();

                    // ::: Obtenemos los registros involucrados en el update :::: //

                    $update_balance_data = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $balance_id_client_update], 
                        ['apiaudiophonebalances_id', '>=', $balance_id_update]
                    ])->get();

                    
                    // :::: Ciclo de Actualización :::: //

                    for($i = 0; $i < $update_balance_count; $i++){

                        if($i == 0){

                            // :::: Obtenemos el balance anterior para el calculo del total de este registro :::: //

                            $prelast_balance_client = ApiAudiophoneBalance::where([
                                ['id_apiaudiophoneclients', $balance_id_client_update], 
                                ['apiaudiophonebalances_id', '<=', $balance_id_update]
                            ])->take(2)->orderBy('apiaudiophonebalances_id', 'desc')->get();

                            $pretotal_last = $prelast_balance_client[1]['apiaudiophonebalances_total'];


                            // :::: Obtenemos el registro a actualizar en la base de datos, los mismos se llenan del request :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($balance_id_update);

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $balance_data_update['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $balance_data_update['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $balance_data_update['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $balance_data_update['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $balance_data_update['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $balance_data_update['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $balance_data_update['apiaudiophonebalances_haber'];

                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal_last + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal_last - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;                               
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }

                            
                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                                
                        }else{

                            // :::: Arreglo Anterior :::: //

                            $arreglo_previo = $i - 1;                         

                            // :::: Obtenemos el id del registro actualizado en la iteración anterior :::: //

                            $id_balance_previo_actualizado = $update_balance_data[$arreglo_previo]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro actualizado en la iteración anterior:::: //

                            $preaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_previo_actualizado);

                            // :::: Obtenemos el total del registro actualizado en la iteración anterior :::: //

                            $pretotal = $preaudiophonebalanceupdate->apiaudiophonebalances_total;

                            // :::: Obtenemos el Id del registro a actualizar posteriores a la primera iteración :::: //

                            $id_balance_actualiza = $update_balance_data[$i]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro a actualizar :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_actualiza);

                            // :::: Procedemos a actualizar el registro :::: //

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $update_balance_data[$i]['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $update_balance_data[$i]['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $update_balance_data[$i]['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $update_balance_data[$i]['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $update_balance_data[$i]['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $update_balance_data[$i]['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $update_balance_data[$i]['apiaudiophonebalances_haber'];


                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;      
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }

                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                          
                        }
                    }

                    return $this->successResponseApiaudiophoneBalanceUpdateDos(true, 200, 'Balance actualizado Satisfactoriamente');
                }
            break;

            default:

            return $this->errorResponse('Metodo no Permitido', 405);
        }
    }


    /**
     * destroy ApiAudiophoneBalance Instance
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function destroyApiaudiophoneBalance(Request $request, $id_apiaudiophoneusers = null){

        // :::: Validación del Request :::: //

        $this->validate($request, [

            'id_apiaudiophoneclients' => 'required|numeric',
            'apiaudiophonebalances_id' => 'required|numeric'
        ]);


        // :::: Obtenemos los datos provenientes del request y el id del cliente a eliminar :::: //

        $balance_data_delete = $request->all();

        // :::: Obtenemos el ID del balance a eliminar de las consultas :::: //

        $balance_delete_id = $balance_data_delete['apiaudiophonebalances_id'];

        // :::: Obtenemos los datos del cliente dueño de ese registro :::: //

        $client_delete_id = $balance_data_delete['id_apiaudiophoneclients'];

        // :::: Obtenemos el rol de usuario :::: //

        $user = ApiAudiophoneUser::userclient($id_apiaudiophoneusers)->first();

        $user_rol = $user->apiaudiophoneusers_role;

        // :::: Obtenemos datos necesarios del ultimo registro de ese cliente :::: //

        $last_balance_client = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $client_delete_id)->get()->last();

        $id_balance_last = $last_balance_client['apiaudiophonebalances_id'];

        // :::: Total de balances por cliente :::: //

        $count_balance_clients = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $client_delete_id)->count();
       
        // :::: Obtenemos los datos necesarios del primer balance para ese cliente, antes de eliminar :::: //

        $first_balance_client = ApiAudiophoneBalance::where([
            ['id_apiaudiophoneclients', $client_delete_id], 
            ['apiaudiophonebalances_id', '>=', $balance_delete_id]
        ])->take(1)->orderBy('apiaudiophonebalances_id', 'asc')->get();        
        
        // :::: Obtenemos el id del primer balance antes de elminar :::: //

        $id_balance_first_delete = $first_balance_client[0]['apiaudiophonebalances_id'];  
        
        // :::: Obtenemos el conteo de registros a partir del actualizado :::: //

        $count_balance_delete = ApiAudiophoneBalance::where([
            ['id_apiaudiophoneclients', $client_delete_id], 
            ['apiaudiophonebalances_id', '>=', $balance_delete_id]
        ])->count();        

        // :::: Procedemos a eliminar el cliente :::: //

        switch($user_rol){

            case('USER_ROLE'):

                return $this->errorResponse('Usuario no autorizado para eliminar Balances', 401);
            break;

            case('ADMIN_ROLE'):

                
                // :::: Eliminación del primer registro de ese cliente :::: //
                if( ($balance_delete_id == $id_balance_first_delete) && ( $count_balance_clients == $count_balance_delete ) ){


                    // ::: Obtenemos los registros involucrados en el update previo a la eliminación :::: //

                    $update_balance_data = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $client_delete_id], 
                        ['apiaudiophonebalances_id', '>=', $balance_delete_id]
                    ])->get();

                    // :::: Obtenemos el id del balance del siguiente registro que no será elminado y que será actualizado :::: //

                    $balance_id_update = $update_balance_data[1]['apiaudiophonebalances_id'];
                    
                    // :::: Ciclo de actualización excepto el primer registro que será eliminado mas abajo :::: //

                    for($i = 1; $i < $count_balance_delete; $i++){

                        if($i == 1){

                            // :::: Obtenemos el registro a actualizar en la base de datos, los mismos se llenan del request :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($balance_id_update);

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $update_balance_data[$i]['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $update_balance_data[$i]['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $update_balance_data[$i]['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $update_balance_data[$i]['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $update_balance_data[$i]['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $update_balance_data[$i]['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $update_balance_data[$i]['apiaudiophonebalances_haber'];

                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = 0 + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = 0 - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;                               
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }
                            
                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                              
                        }else{

                            // :::: Arreglo Anterior :::: //

                            $arreglo_previo = $i - 1;                         

                            // :::: Obtenemos el id del registro actualizado en la iteración anterior :::: //

                            $id_balance_previo_actualizado = $update_balance_data[$arreglo_previo]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro actualizado en la iteración anterior:::: //

                            $preaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_previo_actualizado);

                            // :::: Obtenemos el total del registro actualizado en la iteración anterior :::: //

                            $pretotal = $preaudiophonebalanceupdate->apiaudiophonebalances_total;

                            // :::: Obtenemos el Id del registro a actualizar posteriores a la primera iteración :::: //

                            $id_balance_actualiza = $update_balance_data[$i]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro a actualizar :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_actualiza);

                            // :::: Procedemos a actualizar el registro :::: //

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $update_balance_data[$i]['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $update_balance_data[$i]['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $update_balance_data[$i]['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $update_balance_data[$i]['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $update_balance_data[$i]['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $update_balance_data[$i]['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $update_balance_data[$i]['apiaudiophonebalances_haber'];


                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;                                 
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;                               
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }

                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                          
                        }
                    }                                        
                    
                    // :::: Obtenemos el registro a eliminar y eliminamos para poder recalcular :::: //

                    $apiaudiophonebalancedelete = ApiAudiophoneBalance::findOrFail($balance_delete_id);

                    $apiaudiophonebalancedelete->delete();       
                   
                    return $this->errorResponseApiaudiophonBalanceDestroy(true, 200, 'Balance elminado Satisfactoriamente');


                    // :::: Cuando es el ultimo registro que se va a eliminar :::: //
                }elseif( $balance_delete_id == $id_balance_last ){


                    //dd('prueba eliminando el ultimo registro');

                    $apiaudiophonebalancedelete = ApiAudiophoneBalance::findOrFail($balance_delete_id);

                    $apiaudiophonebalancedelete->delete();                    


                    return $this->errorResponseApiaudiophonBalanceDestroy(true, 200, 'Balance eliminado Satisfactoriamente');

                    // :::: Cuando es un registro intermedio :::: //
                }else{

                    
                    // :::: Consultamos los doatos del registro anterior a ser eliminado para poder recalcular los totales :::: //

                    $prev_balance_delete = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $client_delete_id], 
                        ['apiaudiophonebalances_id', '<=', $balance_delete_id]
                    ])->take(2)->orderBy('apiaudiophonebalances_id', 'asc')->get();


                    // :::: Obtenemos el id del registro previo a ser eliminado :::: //

                    $id_prev_balance_delete = $prev_balance_delete[0]['apiaudiophonebalances_id'];   

                    // :::: Obtenemos el registro a eliminar y eliminamos :::: //

                    $apiaudiophonebalancedelete = ApiAudiophoneBalance::findOrFail($balance_delete_id);

                    $apiaudiophonebalancedelete->delete();

                    // :::: Obtenemos los registros involucrados en el update posterior a la eliminación :::: //

                    $post_update_balance_data_count = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $client_delete_id]
                    ])->count();

                    // :::: Obtenemos los registros involucrados en el update previo a la eliminación :::: //

                    $post_update_balance_data = ApiAudiophoneBalance::where([
                        ['id_apiaudiophoneclients', $client_delete_id],
                        ['apiaudiophonebalances_id', '>=', $id_prev_balance_delete]
                    ])->get();

                    // :::: Ciclo de actualización posterior a la eliminación del registro (se actualiza todo) :::: //

                    for($i = 0; $i < $post_update_balance_data_count; $i++){

                        if($i == 0){

                            // :::: Obtenemos el ID ddel registro a actualizar :::: //

                            $id_post_balance_delete = $post_update_balance_data[$i]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro a actualizar en la base de datos, los mismos se llenan del request :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_post_balance_delete);

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $post_update_balance_data[$i]['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $post_update_balance_data[$i]['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $post_update_balance_data[$i]['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $post_update_balance_data[$i]['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $post_update_balance_data[$i]['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $post_update_balance_data[$i]['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $post_update_balance_data[$i]['apiaudiophonebalances_haber'];

                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = 0 + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = 0 - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;                               
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }
                            
                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                              
                        }else{

                            // :::: Arreglo Anterior :::: //

                            $arreglo_previo = $i - 1;                         

                            // :::: Obtenemos el id del registro actualizado en la iteración anterior :::: //

                            $id_balance_previo_actualizado = $post_update_balance_data[$arreglo_previo]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro actualizado en la iteración anterior:::: //

                            $preaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_previo_actualizado);

                            // :::: Obtenemos el total del registro actualizado en la iteración anterior :::: //

                            $pretotal = $preaudiophonebalanceupdate->apiaudiophonebalances_total;

                            // :::: Obtenemos el Id del registro a actualizar posteriores a la primera iteración :::: //

                            $id_balance_actualiza = $post_update_balance_data[$i]['apiaudiophonebalances_id'];

                            // :::: Obtenemos el registro a actualizar :::: //

                            $apiaudiophonebalanceupdate = ApiAudiophoneBalance::findOrFail($id_balance_actualiza);

                            // :::: Procedemos a actualizar el registro :::: //

                            $apiaudiophonebalanceupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers; 
                            $apiaudiophonebalanceupdate->id_apiaudiophoneclients = $post_update_balance_data[$i]['id_apiaudiophoneclients'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_date = $post_update_balance_data[$i]['apiaudiophonebalances_date'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_desc = $post_update_balance_data[$i]['apiaudiophonebalances_desc'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_horlab = $post_update_balance_data[$i]['apiaudiophonebalances_horlab'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_tarif = $post_update_balance_data[$i]['apiaudiophonebalances_tarif'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_debe = $post_update_balance_data[$i]['apiaudiophonebalances_debe'];
                            $apiaudiophonebalanceupdate->apiaudiophonebalances_haber = $post_update_balance_data[$i]['apiaudiophonebalances_haber'];

                            // :::: Lógica para el llenado del total :::: //

                            if($apiaudiophonebalanceupdate->apiaudiophonebalances_haber == 0){

                            
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal + $apiaudiophonebalanceupdate->apiaudiophonebalances_debe;      
                            }elseif($apiaudiophonebalanceupdate->apiaudiophonebalances_debe == 0){
                               
                                $apiaudiophonebalanceupdate->apiaudiophonebalances_total = $pretotal - $apiaudiophonebalanceupdate->apiaudiophonebalances_haber;
                            }else{

                                return $this->errorResponse('Metodo no Permitido', 405);
                            }

                            // :::: Actualizamos Registro :::: //

                            $apiaudiophonebalanceupdate->update();                          
                        }
                    }

                    return $this->errorResponseApiaudiophonBalanceDestroy(true, 200, 'Balance eliminado Satisfactoriamente');
                }
            break;

            default:

            return $this->errorResponse('Método no Permitido', 405);
        }
    }


    // :::: Generación de PDF registro contable :::: //

    public function pdfBalanceGenerate(Request $request, $id_apiaudiophoneusers = null){

        // :::: Validación del Request :::: //

        $this->validate($request, [

            'id_apiaudiophoneclients' => 'numeric|required'
        ]);

        // :::: Seteamos la zona horaria a Caracas Venezuela :::: //

        date_default_timezone_set('America/Caracas');

        // :::: Establecemos el separador de carpetas por defecto :::: //

        define('DS', DIRECTORY_SEPARATOR);

        // :::: Iniciamos acumulador de horas por el debe :::: //
        
        $acum_hours = 0;

        // :::: Obtenemos los datos provenientes del request y el id del cliente a eliminar :::: //

        $balance_pdf_generate = $request->all();

        // :::: Obtenemos el ID del balance a eliminar de las consultas :::: //

        $balance_pdf_id_client = $balance_pdf_generate['id_apiaudiophoneclients'];

        // :::: Obtenemos la fecha y hora del día :::: //

        $today = date('d-m-Y-H-i-s');

        // :::: Obtenemos los datos del cliente a reportar :::: //

        $apiaudiophoneclientpdf = ApiAudiophoneClient::findOrFail($balance_pdf_id_client);

        $nombre_cliente = $apiaudiophoneclientpdf->apiaudiophoneclients_name;
        
        // :::: Generamos el nombre del balance :::: //

        $nombre_pdf_balance = 'Bal_'.$nombre_cliente.'_'.$today.'.pdf';

        // ::::  Generamos el nombre de la carpeta para guardar la subcarpeta :::: //

        $folder = str_replace('\\', DS, strstr($_SERVER['DOCUMENT_ROOT'], 'apiaudiophone\public', true).'appbal'.DS);

        // :::: Verificamos carpeta, si no existe,  creamos con permisos 777 :::: //

        if(!file_exists($folder)){

            mkdir($folder, 0777, true);
        }

        // :::: Generamos el nombre de la sub carpeta para guardar el balance :::: //

        $sub_folder = $folder.$nombre_cliente.DS;

        //dd($sub_folder);
        // :::: Verificamos carpeta, si no existe,  creamos con permisos 777 :::: //

        if(!file_exists($sub_folder)){

            mkdir($sub_folder, 0777, true);
        }

        //dd($nombre_pdf_balance);
        // :::: Generamos la ruta del balance donde será almacenado el documento :::: //

        $url = $sub_folder.$nombre_pdf_balance;

       //dd($url);
        // :::: Obtenemos el rol de usuario :::: //

        $user = ApiAudiophoneUser::userclient($id_apiaudiophoneusers)->first();

        $user_rol = $user->apiaudiophoneusers_role;

        // :::: Procedemos a eliminar el cliente :::: //

        switch($user_rol){

            case('USER_ROLE'):

                return $this->errorResponse('Usuario no autorizado para imprimir Balances', 401);
            break;

            case('ADMIN_ROLE'):
                
                // :::: Obtenemos los balances del cliente a reportar :::: //

                $apiaudiophonebalancepdf = ApiAudiophoneBalance::where('id_apiaudiophoneclients', $balance_pdf_id_client)
                ->orderBy('apiaudiophonebalances_id', 'asc')
                ->get();

                // :::: Contamos los elementos del arreglo para usar el valor en el for :::: //

                $apiaudiophonebalancepdf_count = count( $apiaudiophonebalancepdf );
                
                // :::: Lógica para sumar las horas laboradas en el debe :::: //

                for ( $i = 0; $i < $apiaudiophonebalancepdf_count; $i++ ) {


                    if ( ( $apiaudiophonebalancepdf[$i]['apiaudiophonebalances_haber'] == 0 ) || ( $apiaudiophonebalancepdf[$i]['apiaudiophonebalances_haber'] == null ) ) {

                        $acum_hours = $acum_hours + $apiaudiophonebalancepdf[$i]['apiaudiophonebalances_horlab'];
                    }
                }

                // :::: Obtenemos el saldo total del balance, primer registro en el front :::: //

                $indice_saldo_final = $apiaudiophonebalancepdf_count - 1; 

                $saldo_final = $apiaudiophonebalancepdf[$indice_saldo_final]['apiaudiophonebalances_total'];     
                     
                // :::: Armamos las variables de salida para tormarlas en el reporte :::: //

                $client_name = $apiaudiophoneclientpdf->apiaudiophoneclients_name;

                $client_ident = $apiaudiophoneclientpdf->apiaudiophoneclients_ident;

                $client_phone = $apiaudiophoneclientpdf->apiaudiophoneclients_phone;
               
                // :::: Actualizamos la url en el modelo para devolverla a la vista :::: //
                
                $apiaudiophoneclientpdf->apiaudiophoneclients_url = $url;

                $apiaudiophoneclientpdf->update();

                // :::: Cargamos la vista y mandamos los datos del balance :::: //

                $pdf = PDF::loadView('balanceview.balance',
                    [
                    'today' => $today,
                    'client_name' => $client_name,
                    'client_ident' => $client_ident,
                    'client_phone' => $client_phone,
                    'acum_hours' => $acum_hours,
                    'saldo_final' => $saldo_final,
                    'apiaudiophonebalancepdf' => $apiaudiophonebalancepdf
                    ]
                )->save($url);

                return $this->pdfBalanceGenerateReport(true, 200, 'Balance Generado, Verificar en el Botón PDF del menú', $url, $folder);
            break;

            default:

            return $this->errorResponse('Método no Permitido', 405);
        }
    }

    // :::: Función que devuelve las llaves de un arreglo :::: //

    public function arrayKeysRequest(array $request_array){


        $array_keys = array_keys($request_array);

        return $array_keys;
    }
}
