<?php

namespace App\Http\Controllers\Apiaudiophonecontrollers;

use App\Traits\ApiResponserTrait;
use App\Apiaudiophonemodels\ApiAudiophoneTerm;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use App\Apiaudiophonemodels\ApiAudiophoneService;
use App\Apiaudiophonemodels\ApiAudiophonEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiAudioPhonEventController extends Controller
{
    
    use ApiResponserTrait;

    /**
     * Display the specified resource.
     *
     * @param  \App\Apiaudiophonemodels\ApiAudiophonEvent  $apiAudiophonEvent
     * @return \Illuminate\Http\Response
     */
    public function showApiAudiophonEvent($id_apiaudiophoneusers = null)
    {

    	//::::: IDENTIFICAMOS SI EXISTEN O NO EVENTOS CREADOS EN LA TABLA :::::://
        
        $bdevent_total = ApiAudiophonEvent::count();

        if($bdevent_total > 0){
    	
	    	//::::: OBTENEMOS EL TIPO DE USUARIO: ADMIN_ROLE Ó USER_ROLE PARA DEVOLVER LOS EVENTOS ::::://

	        $user = ApiAudiophoneUser::select('apiaudiophoneusers_role')->where('apiaudiophoneusers_id', $id_apiaudiophoneusers)->firstOrFail();

	        $user_role = $user->apiaudiophoneusers_role;


	        //::::: OBTENEMOS INICIO Y FIN DE MES ACTUAL (PRIMERA FASE, PARA MANDAR EL MES ACTUAL AL ADMIN_ROLE) ::::://

	        $start_of_month = Carbon::today('America/Caracas')->startOfMonth()->format('Y-m-d');

	        $end_of_month = Carbon::today('America/Caracas')->endOfMonth()->format('Y-m-d');

		   
	        //::: VALIDAMOS EL TIPO DE USUARIO PARA MANDAR LA INFORMACIÓN A LA VISTA :::://

	        switch($user_role){

	        	case('ADMIN_ROLE'):

		        	$all_events_last_month = $this->event_show_last_month($start_of_month, $end_of_month);

		        	return $this->successResponseApiaudiophonEventShow(true, 200,  $all_events_last_month);
	        	  	
	        	  	break;
          // :::: SOLO LOS ADMINISTRADORES PODRÁN CONSULTAR LOS EVENTOS DE TODOS LOS USUARIOS :::: //
	        	case('USER_ROLE'):

	        		/*$all_events_by_user = $this->event_show_by_user($id_apiaudiophoneusers);

	        		return $this->successResponseApiaudiophonEventShow(true, 200,  $all_events_by_user);*/	        		
              return $this->errorResponse('Perfil de usuario no autorizado para consultar eventos', 401);

	        		break;
	        	default:

	        	return $this->errorResponse('Sin registros de Eventos en ApiaudiophonEvent, para el mes en revisión', 404);
        	}        	
        }else{

        	return $this->errorResponse('Sin registros de Eventos en ApiaudiophonEvent, debes crearlo', 404);
        }
    }


    /**
     * Display the service name and last id term.
     *
     * @param  \App\Apiaudiophonemodels\ApiAudiophonEvent  $apiAudiophonEvent
     * @return \Illuminate\Http\Response
     */
    public function createApiAudiophonEvent($id_apiaudiophoneusers = null)
    {
        
      //::::: OBTENEMOS EL TIPO DE USUARIO: ADMIN_ROLE SOLO PERMITIDO PARA CREAR EVENTOS ::::://

      $user = ApiAudiophoneUser::select('apiaudiophoneusers_role')->where('apiaudiophoneusers_id', $id_apiaudiophoneusers)->firstOrFail();

      $user_role = $user->apiaudiophoneusers_role;

      //:::: USAMOS EL CREATE PARA MANDAR A LA VISTA EL ID DEL TERM Y EL NOMBRE DEL SERVICIO DE ESE TERM :::://

      $bdterm_total = ApiAudiophoneTerm::count();


      if($user_role == 'ADMIN_ROLE'){

        //:::: PARA CUANDO EXISTE UN SOLO TERMINO CREADO SE TRAE EL ID DEL ULTIMO TERM CONFIGURADO Y EL ID DEL SERIVIO Y SU NOMBRE :::://

        if($bdterm_total == 1){ 


        	$last_conf_term = ApiAudiophoneTerm::select('apiaudiophoneterms_id', 'id_apiaudiophoneservices')->get()->last();

        	$id_last_conf_term = $last_conf_term->apiaudiophoneterms_id;

        	$id_last_conf_service = $last_conf_term->id_apiaudiophoneservices;

        	$name_service = $last_conf_term->apiaudiophoneservice->apiaudiophoneservices_name;

        
        	switch($last_conf_term->id_apiaudiophoneservices){

  	    		case(1):


  	    			return $this->successResponseApiaudiophonEventCreateOnly(true, 200, 'ID terms ultima configuracion', $id_last_conf_term, $id_last_conf_service, $name_service);
  	    			
  	    			break;	    		
  	    		case(2):


  	    			return $this->successResponseApiaudiophonEventCreateOnly(true, 200, 'ID terms ultima configuracion', $id_last_conf_term, $id_last_conf_service, $name_service);
  	    			break;	    		
  	    		default:
  	    		
  	    		return $this->errorResponse('No existen Terminos o Condiciones para crear eventos', 404);
      		}
        }elseif($bdterm_total > 1){


      	  //:::: CUANDO EXISTEN MAS DE DOS TERMINOS CREADOS, INFORMACION DE LOS ULTIMOS TERMS CONFIGURADOS A LA VISTA, DE LOS SERVICIOS :::://

      	  $last_conf_service_uno = ApiAudiophoneTerm::select('apiaudiophoneterms_id', 'id_apiaudiophoneservices')->where('id_apiaudiophoneservices', 1)->get()->last();

        	$last_conf_service_dos = ApiAudiophoneTerm::select('apiaudiophoneterms_id', 'id_apiaudiophoneservices')->where('id_apiaudiophoneservices', 2)->get()->last();

        	
        	//:::: OBTENEMOS LOS ID DE LOS ULTIMOS TERMS CREADOS PARA AMBOS SERVICIOS :::://

        	$id_last_conf_service_uno = $last_conf_service_uno->apiaudiophoneterms_id;

        	$id_last_conf_service_dos = $last_conf_service_dos->apiaudiophoneterms_id;


        	//:::: OBTENEMOS EL NOMBRE DE LOS ULTIMOS SERVICIOS CONFIGURADOS :::://
        	
        	$nombre_servicio_uno = $last_conf_service_uno->apiaudiophoneservice->apiaudiophoneservices_name;

        	$nombre_servicio_dos = $last_conf_service_dos->apiaudiophoneservice->apiaudiophoneservices_name;


        	return $this->successResponseApiaudiophonEventCreate(true, 200, 'ID terms ultima configuracion', $id_last_conf_service_uno, $nombre_servicio_uno, $id_last_conf_service_dos, $nombre_servicio_dos);
        }else{


        	return $this->errorResponse('No existen Terminos o Condiciones para crear eventos', 404);
        }

        // ::: TAREA: HACER UN BUCLE QUE RECORRA LA EL MODELO DE SERVICIOS Y VAYA LLENANDO LAS VARIABLES DE SELECCION DE TERMINOS, DE ID Y DE NOMBRE DE SERVICIOS PARA LUEGO ENVIARLOS A LA VISTA ::: //
      }else{

        return $this->errorResponse('Usuario no autorizado para el módulo de eventos', 401);
      }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $id_apiaudiophoneusers
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeApiAudiophonEvent(Request $request, $id_apiaudiophoneusers = null)
    {        

      //::::: Validación del Request ::::://
      
      $this->validate($request, [

          'id_apiaudiophoneservices' => 'required|numeric',
          'id_apiaudiophoneterms' => 'required|numeric',
          'apiaudiophonevents_title' => 'required|string|max:120',
          'apiaudiophonevents_address' => 'string|max:120',
          'apiaudiophonevents_description' => 'required|string|max:120',
          'apiaudiophonevents_date' => 'required|date_format:Y-m-d',
          'apiaudiophonevents_begintime' => 'required|date_format:H:i',
          'apiaudiophonevents_finaltime' => 'required|date_format:H:i',
          'apiaudiophonevents_totalhours' => 'required|date_format:H:i'
      ]);

       	
      //:::: CAPTURAMOS EL REQUEST :::://

      $apiaudiophoneventdata = $request->all();
     	
	
	    //:::: OBTENEMOS PRIMER DIA DEL MES ACTUAL ::::://

     	$start_month = Carbon::today('America/Caracas')->startOfMonth()->format('Y-m-d');

     	
		  //:::: OBTENEMOS UTLIMO DIA DEL MES ACTUAL :::://

     	$end_month = Carbon::today('America/Caracas')->endOfMonth()->format('Y-m-d');


     	//:::: OBTENEMOS PRIMER DIA DE LA SEMANA ACTUAL :::://

     	$start_week = Carbon::today('America/Caracas')->startOfWeek()->format('Y-m-d');

     	
     	//:::: OBTENEMOS EL ULTIMO DÍA DE LA SEMANA ACTUAL :::://

     	$end_week = Carbon::today('America/Caracas')->endOfWeek()->format('Y-m-d');

    
     	//:::: OBTENEMOS CONTEO DE CITAS SEMANALES GENERADAS POR EL USUARIO :::://

     	$weekly_count = $this->event_count_by_user($id_apiaudiophoneusers, $apiaudiophoneventdata['id_apiaudiophoneservices'], $start_week, $end_week);       
      
      
      //:::: OBTENEMOS CONTEO DE CITAS SEMANALES GENERADAS POR EL USUARIO :::://

     	$monthly_count = $this->event_count_by_user($id_apiaudiophoneusers, $apiaudiophoneventdata['id_apiaudiophoneservices'], $start_month, $end_month);
      

      //:::: OBTENEMOS EL DIA ACTUAL :::://

      $today = Carbon::today('America/Caracas')->format('Y-m-d');


    	//:::: BUSCAMOS EL NOMBRE DEL SERVICIO DE ACUERDO AL ID_SERVICES DEL REQUEST ::::://

    	$apiaudiophonevent_service_name = $this->service_name($apiaudiophoneventdata['id_apiaudiophoneservices']);

    	
    	//:::: OBTENER BEGIN TIME DE LA ULTIMA CONFIGURACION DEL TERM DE ACUERDO AL ID DEL TERM :::://

    	$btime = $this->begin_time_last_configuration($apiaudiophoneventdata['id_apiaudiophoneterms']);
    	
    
    	//:::: OBTENER FINAL TIME DE LA ULTIMA COMFIGURACION DEL TERM DE ACUERDO AL ID DEL TERM :::://

    	$ftime = $this->final_time_last_configuration($apiaudiophoneventdata['id_apiaudiophoneterms']);
    

    	//:::: OBTENER LOS DIAS PERMITIDOS DE LA ULTIMA CONFIGURACION DEL TERM DE ACUERDO AL ID DEL TERM ::::://

  		$apiaudiophoneterm_day = $this->days_event_term($apiaudiophoneventdata['id_apiaudiophoneterms']);

  		
  		//:::: TRANSFORMAMOS EN STRING EL ARREGLO DE DIAS PARA MANDARLO DE VUELTA EN LA VALIDACIÓN ::::://    	

  		$apiaudiophoneterm_day_str = implode(',', $apiaudiophoneterm_day);


    	//::::: CONTAMOS CANTIDAD DE DÍAS CONFIGURADOS EN EL TERM ::::://	

    	$quantity_days = count($apiaudiophoneterm_day);


    	//::::: OBTENEMOS EL RANGO DE DÍAS CONFIGURADO EN EL TERM ::::://

    	$rank_event = $this->rank_day_last_configuration($apiaudiophoneventdata['id_apiaudiophoneterms']);
    	

    	//::::: OBTENEMOS EL DÍA DE LA SEMANA DE ACUERDO A LA FECHA DEL REQUEST ::::://

	    $week_day_event_date = $this->day_week($apiaudiophoneventdata['apiaudiophonevents_date']);


	    //::::: OBTENEMOS LA CANTIDAD DE DIAS SEMANALES CONFIGURADOS PARA EL EVENTO ::::://

	    $quantity_events_weekly = $this->quantity_weekly_day_last_configuration($apiaudiophoneventdata['id_apiaudiophoneterms']);
  

	    //::::: OBTENEMOS LA CANTIDAD DE DIAS MENSUALES CONFIGURADOS PARA EL EVENTO ::::://

	    $quantity_events_monthly = $this->quantity_monthly_day_last_configuration($apiaudiophoneventdata['id_apiaudiophoneterms']);
       

      //::::: OBTENEMOS EL TIPO DE USUARIO: ADMIN_ROLE Ó USER_ROLE PARA generación de eventos ::::://

      $user = ApiAudiophoneUser::select('apiaudiophoneusers_role')->where('apiaudiophoneusers_id', $id_apiaudiophoneusers)->firstOrFail();

      $user_role_new = $user->apiaudiophoneusers_role;


	    //:::: CREAMOS UNA INSTANCIA DEL APIAUDIOPHONEVENT :::://

    	$apiaudiophoneventnew = new ApiAudiophonEvent;

		
		  $apiaudiophoneventnew->id_apiaudiophoneusers = $id_apiaudiophoneusers;
		  $apiaudiophoneventnew->id_apiaudiophoneservices = $apiaudiophoneventdata['id_apiaudiophoneservices'];
		  $apiaudiophoneventnew->id_apiaudiophoneterms = $apiaudiophoneventdata['id_apiaudiophoneterms'];		    	
    	$apiaudiophoneventnew->apiaudiophonevents_title = $apiaudiophoneventdata['apiaudiophonevents_title'];
    	$apiaudiophoneventnew->apiaudiophonevents_address = $apiaudiophoneventdata['apiaudiophonevents_address'];
    	$apiaudiophoneventnew->apiaudiophonevents_description = $apiaudiophoneventdata['apiaudiophonevents_description'];
    	$apiaudiophoneventnew->apiaudiophonevents_totalhours = $apiaudiophoneventdata['apiaudiophonevents_totalhours'];

    	
    	//:::: VALIDACION PARA ALMACENAR EL BEGIN TIME DEL EVENTO, DEBE SER MAYOR O IGUAL A LA DEL TERM :::://

    	if(($apiaudiophoneventdata['apiaudiophonevents_begintime']) >= $btime){


    		$apiaudiophoneventnew->apiaudiophonevents_begintime = $apiaudiophoneventdata['apiaudiophonevents_begintime'];
    	}else{

    		return $this->errorResponse('Hora de Inicio debe ser mayor o igual a la(s):'.$btime, 400);
    	}

		  //:::: VALIDACION PARA ALMACENAR EL FINAL TIME DEL EVENTO, DEBE SER MENOR O IGUAL A LA DEL TERM :::://

    	if(($apiaudiophoneventdata['apiaudiophonevents_finaltime']) <= $ftime){

    		
    		$apiaudiophoneventnew->apiaudiophonevents_finaltime = $apiaudiophoneventdata['apiaudiophonevents_finaltime'];
    	}else{

    		return $this->errorResponse('Hora de Finalizacion debe ser menor o igual a la(s):'.$ftime, 400);
    	}


    	//:::: LOGICA DE VALIDACION PARA ALMACENAR EL DATE DEL EVENTO, DEBE COINCIDIR CON LOS DIAS DEL TERM :::://

    	
      if($apiaudiophoneventdata['apiaudiophonevents_date'] >= $today){

        switch($rank_event){

      		case('5-days'):

      			if(($week_day_event_date != 'sabado') && ($week_day_event_date != 'domingo')){

  					$apiaudiophoneventnew->apiaudiophonevents_date = $apiaudiophoneventdata['apiaudiophonevents_date'];    				
      			}else{

      				return $this->errorResponse('Los dias permitidos son de lunea a viernes', 400);
      			}
      			
      			break;
      		case('range'):

      			if(in_array($week_day_event_date, $apiaudiophoneterm_day)){


      				$apiaudiophoneventnew->apiaudiophonevents_date = $apiaudiophoneventdata['apiaudiophonevents_date'];
      			}else{


      				return $this->errorResponse('Los dias permitidos son: '.$apiaudiophoneterm_day_str, 400);
      			}
      			break;
      		default:
      		
      		$apiaudiophoneventnew->apiaudiophonevents_date = $apiaudiophoneventdata['apiaudiophonevents_date'];
      	}
      }else{

        return $this->errorResponse('La fecha del evento debe ser mayor o igual a: '.$today, 400);
      }
     

    	//:::: LOGICA DE VALIDACION QUE RESTRINGE LA GENERACIÓN DE CITAS POR USUARIO, DE ACUERDO A LO INDICADO POR EL TERM :::://
      		
      	
    	if($weekly_count >= $quantity_events_weekly){

      
      	return $this->errorResponseQuantityEvents(true, 405, 'Superaste las solicitudes de citas semanales, solo se permiten: '.$quantity_events_weekly);
    	}elseif($monthly_count >= $quantity_events_monthly){

    	 
		   return $this->errorResponseQuantityEvents(true, 405, 'Superaste las solicitudes de citas mensuales, solo se permiten:'.$quantity_events_monthly);

      //:::: SOLO ADMIN_ROLE PUEDE CREAR EVENTOS ::::// 
            	
     	}elseif($user_role_new == 'ADMIN_ROLE'){
      	
    
    	  $apiaudiophoneventnew->save();

    	  return $this->successResponseApiaudiophonEventStore(true, 201, 'Evento Creado Exitosamente', $apiaudiophonevent_service_name, $apiaudiophoneventnew);
      }else{
        
        return $this->errorResponse('Usuario no es administrador, no puede crear eventos', 400);
     	}
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param $id_apiaudiophoneusers
     * @param \Illuminate\Http\Request $request 
     * @return \Illuminate\Http\Response
     */
    public function updateApiAudiophonEvent(Request $request, $id_apiaudiophoneusers)
    {

      //::::: Validación del Request ::::://
      
      $this->validate($request, [

          'id_apiaudiophoneservices' => 'required|numeric',
          'id_apiaudiophoneterms' => 'required|numeric',
          'apiaudiophonevents_id' => 'required|numeric',
          'apiaudiophonevents_title' => 'required|string|max:120',
          'apiaudiophonevents_address' => 'string|max:120',
          'apiaudiophonevents_description' => 'required|string|max:120',
          'apiaudiophonevents_date' => 'required|date_format:Y-m-d',
          'apiaudiophonevents_begintime' => 'required|date_format:H:i',
          'apiaudiophonevents_finaltime' => 'required|date_format:H:i',
          'apiaudiophonevents_totalhours' => 'required|date_format:H:i'
      ]);

      //:::: CAPTURAMOS EL REQUEST :::://

      $apiaudiophoneventdata_upd = $request->all();


    	//:::: BUSCAMOS EL NOMBRE DEL SERVICIO DE ACUERDO AL ID_SERVICES DEL REQUEST ::::://

    	$apiaudiophonevent_service_name = $this->service_name($apiaudiophoneventdata_upd['id_apiaudiophoneservices']);

    
      //:::: OBTENEMOS EL DIA ACTUAL :::://

      $today_upd = Carbon::today('America/Caracas')->format('Y-m-d');


    	//:::: OBTENER BEGIN TIME DE LA ULTIMA CONFIGURACION DEL TERM DE ACUERDO AL ID DEL TERM :::://

    	$btime = $this->begin_time_last_configuration($apiaudiophoneventdata_upd['id_apiaudiophoneterms']);
    	
    
    	//:::: OBTENER FINAL TIME DE LA ULTIMA COMFIGURACION DEL TERM DE ACUERDO AL ID DEL TERM :::://

    	$ftime = $this->final_time_last_configuration($apiaudiophoneventdata_upd['id_apiaudiophoneterms']);
    

    	//:::: OBTENER LOS DIAS PERMITIDOS DE LA ULTIMA CONFIGURACION DEL TERM DE ACUERDO AL ID DEL TERM ::::://

  		$apiaudiophoneterm_day = $this->days_event_term($apiaudiophoneventdata_upd['id_apiaudiophoneterms']);


  		//:::: TRANSFORMAMOS EN STRING EL ARREGLO DE DIAS PARA MANDARLO DE VUELTA EN LA VALIDACIÓN - RESPONSES ::::://    	

  		$apiaudiophoneterm_day_str = implode(',', $apiaudiophoneterm_day);

    	//::::: CONTAMOS CANTIDAD DE DÍAS CONFIGURADOS EN EL TERM ::::://	

    	$quantity_days = count($apiaudiophoneterm_day);


    	//::::: OBTENEMOS EL RANGO DE DÍAS CONFIGURADO EN EL TERM ::::://

    	$rank_event = $this->rank_day_last_configuration($apiaudiophoneventdata_upd['id_apiaudiophoneterms']);
    	

    	//::::: OBTENEMOS EL DÍA DE LA SEMANA DE ACUERDO A LA FECHA DEL REQUEST ::::://

	    $week_day_event_date = $this->day_week($apiaudiophoneventdata_upd['apiaudiophonevents_date']);


	    //::::: OBTENEMOS LA CANTIDAD DE DIAS SEMANALES CONFIGURADOS PARA EL EVENTO ::::://

	    $quantity_events_weekly = $this->quantity_weekly_day_last_configuration($apiaudiophoneventdata_upd['id_apiaudiophoneterms']);
  

	    //::::: OBTENEMOS LA CANTIDAD DE DIAS MENSUALES CONFIGURADOS PARA EL EVENTO ::::://

	    $quantity_events_monthly = $this->quantity_monthly_day_last_configuration($apiaudiophoneventdata_upd['id_apiaudiophoneterms']);


      //::::: OBTENEMOS EL TIPO DE USUARIO ::::://

      $user_upd = ApiAudiophoneUser::select('apiaudiophoneusers_role')->where('apiaudiophoneusers_id', $id_apiaudiophoneusers)->firstOrFail();

      $user_role_upd = $user_upd->apiaudiophoneusers_role;


	    //:::: OBTENEMOS LA INSTANCIA DEL APIAUDIOPHONEVENT A ACTUALIZAR :::://

    	$apiaudiophoneventupdate = ApiAudiophonEvent::findOrFail($apiaudiophoneventdata_upd['apiaudiophonevents_id']);

		
  		$apiaudiophoneventupdate->id_apiaudiophoneusers = $id_apiaudiophoneusers;
  		$apiaudiophoneventupdate->id_apiaudiophoneservices = $apiaudiophoneventdata_upd['id_apiaudiophoneservices'];
  		$apiaudiophoneventupdate->id_apiaudiophoneterms = $apiaudiophoneventdata_upd['id_apiaudiophoneterms'];		    	
    	$apiaudiophoneventupdate->apiaudiophonevents_title = $apiaudiophoneventdata_upd['apiaudiophonevents_title'];
    	$apiaudiophoneventupdate->apiaudiophonevents_address = $apiaudiophoneventdata_upd['apiaudiophonevents_address'];
    	$apiaudiophoneventupdate->apiaudiophonevents_description = $apiaudiophoneventdata_upd['apiaudiophonevents_description'];
    	$apiaudiophoneventupdate->apiaudiophonevents_totalhours = $apiaudiophoneventdata_upd['apiaudiophonevents_totalhours'];

    	
    	//:::: VALIDACION PARA ACTUALIZAR EL BEGIN TIME DEL EVENTO, DEBE SER MAYOR O IGUAL A LA DEL TERM :::://

    	if(($apiaudiophoneventdata_upd['apiaudiophonevents_begintime']) >= $btime){

    		
    		$apiaudiophoneventupdate->apiaudiophonevents_begintime = $apiaudiophoneventdata_upd['apiaudiophonevents_begintime'];
    	}else{

    		return $this->errorResponse('Hora de Inicio debe ser mayor o igual a la(s):'.$btime, 400);
    	}

		  //:::: VALIDACION PARA ACTUALIZAR EL FINAL TIME DEL EVENTO, DEBE SER MENOR O IGUAL A LA DEL TERM :::://

    	if(($apiaudiophoneventdata_upd['apiaudiophonevents_finaltime']) <= $ftime){

    		
    		$apiaudiophoneventupdate->apiaudiophonevents_finaltime = $apiaudiophoneventdata_upd['apiaudiophonevents_finaltime'];
    	}else{

    		return $this->errorResponse('Hora de Finalizacion debe ser menor o igual a la(s):'.$ftime, 400);
    	}

    	//:::: APLICA LOGICA DE VALIDACION PARA ACTUALIZAR EL DATE DEL EVENTO, DEBE COINCIDIR CON LOS DIAS DEL TERM :::://

      if($apiaudiophoneventdata_upd['apiaudiophonevents_date'] >= $today_upd){

      	switch($rank_event){

      		case('5-days'):

      			if(($week_day_event_date != 'sabado') && ($week_day_event_date != 'domingo')){

  					$apiaudiophoneventupdate->apiaudiophonevents_date = $apiaudiophoneventdata_upd['apiaudiophonevents_date'];    				
      			}else{

      				return $this->errorResponse('Los dias permitidos son de lunea a viernes', 400);
      			}
      			
      			break;
      		case('range'):

      			if(in_array($week_day_event_date, $apiaudiophoneterm_day)){

      				$apiaudiophoneventupdate->apiaudiophonevents_date = $apiaudiophoneventdata_upd['apiaudiophonevents_date'];
      			}else{

      				return $this->errorResponse('Los dias permitidos son: '.$apiaudiophoneterm_day_str, 400);
      			}
      			break;      		
          default:
      		
          $apiaudiophoneventupdate->apiaudiophonevents_date = $apiaudiophoneventdata_upd['apiaudiophonevents_date'];
      	}
      }else{

        return $this->errorResponse('La fecha del evento debe ser mayor o igual a: '.$today_upd, 400);
      }

    	if($user_role_upd == 'ADMIN_ROLE'){

        $apiaudiophoneventupdate->update();

      	return $this->successResponseApiaudiophonEventUpdate(true, 201, 'Evento Actualizado Exitosamente', $apiaudiophonevent_service_name, $apiaudiophoneventupdate);
      }else{

        return $this->errorResponse('Usuario no es administrador, no puede actualizar eventos', 400);
      }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $id_apiaudiophoneusers
     * @param \Illuminate\Http\Request $request 
     * @return \Illuminate\Http\Response
     */
    public function updateStatusEvent(Request $request, $id_apiaudiophoneusers)
    {

  	 //::::: Validación del Request ::::://
      
      $this->validate($request, [

      	'apiaudiophonevents_id' => 'required|numeric',
        'apiaudiophonevents_status' => 'required|regex:([A-Z])'            
      ]);

      //:::: OBTENEMOS DATOS DEL EVENTO PARA ACTUALIZAR ESTADO :::://

      $event_data = $request->all();

      
      //:::: OBTENEMOS DATOS DEL USUARIO, DEBE SER ADMIN_ROLE Y ESTAR ACTIVO PARA PODER GESTIONAR :::://

      $apiaudiophoneuser_data = ApiAudiophoneUser::findOrFail($id_apiaudiophoneusers);

    	$status = $apiaudiophoneuser_data->apiaudiophoneusers_status;

    	$role = $apiaudiophoneuser_data->apiaudiophoneusers_role;


    	//:::: OBTENEMOS EL EVENTO A ACTUALIZAR A TRAVÉS DEL ID :::://

    	$event_update = ApiAudiophonEvent::findOrFail($event_data['apiaudiophonevents_id']);

    	
    	//:::: VALIDAMOS ESTATUS Y ROL DEL USUARIO PARA ACTUALIZAR EL ESTATUS DEL EVENTO :::://

    	if(($status == true) && ($role == 'ADMIN_ROLE')){

    		
    		switch($event_data['apiaudiophonevents_status']){

    			case('ACEPTADO'):

    				$event_update->apiaudiophonevents_status = $event_data['apiaudiophonevents_status'];
    				
    				break;
    			case('POSPUESTO'):

    				$event_update->apiaudiophonevents_status = $event_data['apiaudiophonevents_status'];
    				
    				break;
    			case('RECHAZADO'):

    				$event_update->apiaudiophonevents_status = $event_data['apiaudiophonevents_status'];
    				break;
    			case('CERRADO'):

    				$event_update->apiaudiophonevents_status = $event_data['apiaudiophonevents_status'];
    				break;
    			default:

    			return $this->errorResponse('El estatus '.$event_data['apiaudiophonevents_status'].' no esta permitido para ser almacenado', 422);
    		}

    		$event_update->update();

    		return $this->successResponseApiaudiophoneUserStore(true, 201, 'Estatus actualizado a: '.$event_update->apiaudiophonevents_status, $event_update);
    	}else{

    		return $this->errorResponse('Usuario no Autorizado para actualizar estado del evento', 401);
    	}
    }	


    /**
     * Remove the specified resource from storage.
     *
     * @param $id_apiaudiophoneusers
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroyApiAudiophonEvent(Request $request, $id_apiaudiophoneusers)
    {
        
    	//::::: Validación del Request ::::://
        
      $this->validate($request, [

          'apiaudiophonevents_id' => 'required|numeric'
      ]);


      $id_event = $request->input('apiaudiophonevents_id');

      $apiaudiophoneuserevent = ApiAudiophoneUser::findOrFail($id_apiaudiophoneusers);

    	$user_status = $apiaudiophoneuserevent->apiaudiophoneusers_status;
      
      $user_role = $apiaudiophoneuserevent->apiaudiophoneusers_role;


      if($user_role == 'ADMIN_ROLE'){        

      	switch($user_status){

      		case false:

      			return $this->errorResponse('No se pudo eliminar el registro, Usuario Inactivo', 401);
      		break;

      		case true:

      			//:::: OBTENEMOS ULTIMO REGISTRO DE LA BASE DE DATOS Y LO ELIMINAMOS ::::://

      			$apiaudiophoneventdelete = ApiAudiophonEvent::findOrFail($id_event);

      			$apiaudiophoneventdelete->delete();
      			
      			return $this->errorResponseApiaudiophonEventDestroy(true, 200, 'Evento eliminado Satisfactoriamente');    			
      		break;

      		default:

      		return $this->errorResponse('Peticion mal realizada en la URL, incluir ID del usuario', 400);
      	}
      }else{

        return $this->errorResponse('Usuario no Autorizado para eliminar eventos', 401);
      }
    }

    /*  
     * Funcion que transforma los días de eventos en un arreglo
    */
    public function string_to_array($string_days_events){

    	return explode(', ', $string_days_events);
    }


    /*  
     * Funcion que recibe la fecha numerica y te devuelve el dia de la semana
    */
    public function day_week($string_fecha){

    	//necesitamos llegar al indice 7 en el arreglo de días para que tome como ultimo dia de la semana el valor 'domingo'
    	//de lo contrario dará un mensaje de error porque la funcion strtotime maneja como ultimo dia de la semana el indice 7
    	//el indice 0 lo podemos llenar con cualquier valor en este caso le dejamos domingo...

    	$dias_semana = ['domingo','lunes','martes','miercoles','jueves','viernes','sabado','domingo'];

    	$dia_correspondiente = $dias_semana[date('N', strtotime($string_fecha))];

    	return $dia_correspondiente;
    }

	
    /*  
     * Funcion que devuelve el nombre del servicio partiendo de un scope en el modelo service
    */
    public function service_name($id_service){

    	
    	$apiaudiophonevent_query = ApiAudiophoneService::servicename($id_service)->first();

    	$service_name = $apiaudiophonevent_query->apiaudiophoneservices_name;

    	return $service_name;
    }


    /*  
     * Funcion que a traves del ID term devuelve los dias de eventos configurados en el terms
    */
    public function days_event_term($id_term){

    	
    	$apiaudiophoneterm_day_event = ApiAudiophoneTerm::where('apiaudiophoneterms_id', $id_term)->first();

    	$term_days_event = $apiaudiophoneterm_day_event->apiaudiophoneterms_daysevents;

    	$daysevent_term_array = $this->string_to_array($term_days_event);

    	return $daysevent_term_array;

    }

	/*  
     * Funcion que a traves del ID term y devuelve el begin time configurado en el term
    */
    public function begin_time_last_configuration($id_term){

    	
    	$apiaudiophonetermbtime = ApiAudiophoneTerm::where('apiaudiophoneterms_id', $id_term)->first();

    	$initime = $apiaudiophonetermbtime->apiaudiophoneterms_begintime;

    	list($hour, $minutes) = explode(':', $initime);

    	return $hour.':'.$minutes;
    }

    /*  
     * Funcion que a traves del ID term y devuelve el final time configurado en el term
    */
    public function final_time_last_configuration($id_term){

    	
    	$apiaudiophonetermftime = ApiAudiophoneTerm::where('apiaudiophoneterms_id', $id_term)->first();

    	$fintime = $apiaudiophonetermftime->apiaudiophoneterms_finaltime;

    	list($hour, $minutes) = explode(':', $fintime);

    	return $hour.':'.$minutes;
    }

    /*  
     * Funcion que a traves del ID term y devuelve el rango de días configurado en el term
    */
    public function rank_day_last_configuration($id_term){

    	
    	$apiaudiophoneterm_rday = ApiAudiophoneTerm::where('apiaudiophoneterms_id', $id_term)->first();

    	$rankday = $apiaudiophoneterm_rday->apiaudiophoneterms_rankevents;

    	return $rankday;
    }

    /*  
     * Funcion que a traves del ID term y devuelve cantidad de eventos semanales configurado en el term
    */
    public function quantity_weekly_day_last_configuration($id_term){

    	
    	$apiaudiophoneterm_day_weeekly = ApiAudiophoneTerm::where('apiaudiophoneterms_id', $id_term)->first();

    	$qweeklyday = $apiaudiophoneterm_day_weeekly->apiaudiophoneterms_quantityeventsweekly;

    	return $qweeklyday;
    }


    /*  
     * Funcion que a traves del ID term y devuelve cantidad de eventos mensuales configurados en el term
    */
    public function quantity_monthly_day_last_configuration($id_term){

    	
    	$apiaudiophoneterm_day_monthly = ApiAudiophoneTerm::where('apiaudiophoneterms_id', $id_term)->first();

    	$qmonthlyday = $apiaudiophoneterm_day_monthly->apiaudiophoneterms_quantityeventsmonthly;

    	return $qmonthlyday;
    }


    /*  
     * Funcion que cuenta el numero de eventos para un usuario y servicio determinado
    */
    public function event_count_by_user($id_user, $id_ser, $begin, $finish){

      
      $count_week = ApiAudiophonEvent::whereBetween('created_at', [$begin, $finish])->where('id_apiaudiophoneservices', $id_ser)->whereIn('id_apiaudiophoneusers', [$id_user])->count();
      

    	return $count_week;
    }


    /*  
     * Funcion que devuelve todos los eventos del utlimo mes para todos los usuarios para ADMIN_ROLE
    */
    public function event_show_last_month($begin, $finish){

    	
    	$event_last_month = ApiAudiophonEvent::whereBetween('created_at', [$begin, $finish])->orderBy('created_at', 'desc')->get();

    	return $event_last_month;
    }


    /*  
     * Funcion que devuelve todos los eventos del utlimo mes para todos los usuarios para ADMIN_ROLE
    */
    public function event_show_by_user($iduser){

    	
    	$event_by_user = ApiAudiophonEvent::where('id_apiaudiophoneusers', $iduser)->orderBy('created_at', 'desc')->get();

    	return $event_by_user;
    }
}
