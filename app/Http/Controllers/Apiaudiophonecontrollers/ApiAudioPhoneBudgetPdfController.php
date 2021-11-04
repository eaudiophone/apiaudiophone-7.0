<?php

namespace App\Http\Controllers\Apiaudiophonecontrollers;

use App\Traits\ApiResponserTrait;
use App\Http\Controllers\Controller;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use App\Apiaudiophonemodels\ApiAudiophoneService;
use App\Apiaudiophonemodels\ApiAudiophoneItem;
use App\Apiaudiophonemodels\ApiAudiophoneBudget;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class ApiAudioPhoneBudgetPdfController extends Controller
{

	 use ApiResponserTrait;
     
	/**
	 * Show ApiaudiophoneBudgets Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/
    public function showApiaudiophoneBudget(Request $request, $id_apiaudiophoneusers = null)
	{

		//:::: Validación del request :::://

		$this->validate($request, [

			'start' => 'numeric',
			'end'   => 'numeric',
			'stringsearch' => 'string'
		]);


		//:::: Capturamos el contenido del request :::://

		$budget_data_show = $request->all();
		
		//:::: Contamos elementos del request :::://

		$parameters_total = count($budget_data_show);

		//:::: Contamos los registros de la tabal Items :::://

		$bd_budget_total = ApiAudiophoneBudget::count();

		// :::: Obtenemos el rol de usuario :::: //

        $user = ApiAudiophoneUser::budgetuser($id_apiaudiophoneusers)->firstOrFail();

		$user_budget_rol = $user->apiaudiophoneusers_role;

		
		if($user_budget_rol == 'ADMIN_ROLE'){

			switch($bd_budget_total){

				case(0):

					return $this->errorResponse('No Existen Presupuestos, debe crearlos', 404);

					break;
				default:

				// :::: Aplicamos misma lógica del show item :::: //

				if(($parameters_total == 1) && (key($budget_data_show) == 'stringsearch')){

					$chain = $budget_data_show['stringsearch'];

					// :::: Cuando es la primera consulta, la cadena el request esta vacía y e menor o igual de 15 budgets :::: //

					if(!($chain) && ($bd_budget_total <= 15)){						
					
						// :::: Eviamos los budgets creados a la vista :::: //

						$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->orderBy('apiaudiophonebudgets_id','asc')->get();


						return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);

						
						// :::: Cuando es la primera consulta, la cadena el request tiene un espacio en blanco y hay menor o igual a 15 budgets :::: //
					}elseif((ctype_space($chain) == true) && ($bd_budget_total <= 15)){


						// :::: Eviamos los Budgets creados a la vista :::: //

						$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->orderBy('apiaudiophonebudgets_id','asc')->get();


						return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);
					

						// :::: Cuando es la primera consulta, la cadena el request tiene un espacio en blanco y existen igual o mas de 15  budgets :::: //
					}elseif((ctype_space($chain) == true) && ($bd_budget_total >= 15)){


						// :::: Eviamos los budgets creados a la vista :::: //

						$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price','apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->whereBetween('apiaudiophonebudgets_id', [1, 15])->orderBy('apiaudiophonebudgets_id','asc')->get();


						return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);
					
						// :::: Cuando el stringsearch está vacío y hay igual o mas de 15 budgets :::: //
					}elseif(!($chain) && ($bd_budget_total >= 15)){

						// :::: Eviamos los Items creados a la vista :::: //

						$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->whereBetween('apiaudiophonebudgets_id', [1, 15])->orderBy('apiaudiophonebudgets_id','asc')->get();


						return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);
					
						// :::: Cuando existe una busqueda por stringsearch asumimos que hay mas de 5 usuarios :::: //
					}else{
					
						// :::: Contamos los Elementos que se obtienen para busqueda stringsearch :::: //

						$apiaudiophonebudgetdatacount = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->where('apiaudiophonebudgets_client_name', 'like', '%'.$chain.'%')->orWhere('apiaudiophonebudgets_client_email', 'like', '%'.$chain.'%')->count();

						// :::: Eviamos los budgets creados a la vista :::: //

						$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->where('apiaudiophonebudgets_client_name', 'like', '%'.$chain.'%')->orWhere('apiaudiophonebudgets_client_email', 'like', '%'.$chain.'%')->orderBy('apiaudiophonebudgets_id', 'asc')->get();


						return $this->successResponseApiaudiophoneBudget(true, 200, $apiaudiophonebudgetdatacount, $apiaudiophonebudgetdata); 
						
					}
				}elseif($parameters_total == 2){

					// :::: Obtenemos las Key del request :::: //

					$keys_budget_data_show = $this->arrayKeysRequest($budget_data_show);

					// :::: Validamos que lo recibido por el Request sean start y end en el request :::: //

					if(($keys_budget_data_show[0] == 'start') && ($keys_budget_data_show[1] == 'end')){

						$start = $budget_data_show['start'];
						$end = $budget_data_show['end'];

						// :::: Cuando están vacíos los parametros de búsqueda, devuelve los primeros 15:::: //

						if(!($start) && !($end)){
						
							// :::: Eviamos los Budgets creados a la vista :::: //

							$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->whereBetween('apiaudiophonebudgets_id', [1, 15])->orderBy('apiaudiophonebudgets_id','asc')->get();


							return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);

							// :::: Cuando está vacío uno de los parametros de búsqueda, devuelve los primeros 15:::: //

						}elseif(!($start) || !($end)){
						
							// :::: Eviamos los Budgets creados a la vista :::: //

							$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status','created_at')->whereBetween('apiaudiophonebudgets_id', [1, 15])->orderBy('apiaudiophonebudgets_id','asc')->get();


							return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);					
						}else{
						
							// :::: Eviamos los budgets creados a la vista :::: //

							$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->whereBetween('apiaudiophonebudgets_id', [$start, $end])->orderBy('apiaudiophonebudgets_id','asc')->get();


							return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);
						}
					}else{


						return $this->errorResponse('Elementos del Request no Corresponden', 400);
					}

					// :::: Primera consulta si hay igual o menos de 15 budgets creados en la BD :::: //					
				}elseif($parameters_total == 0){

					if($bd_budget_total <= 15){						

						$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status', 'created_at')->orderBy('apiaudiophonebudgets_id','asc')->get();

						return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);

						// :::: Cuando es la primera consulta, sin parametros y hay mas de 15 budgets :::: //
					}else{							

						// :::: Enviamos los Budgets creados a la vista :::: //

						$apiaudiophonebudgetdata = ApiAudiophoneBudget::select('apiaudiophonebudgets_id','apiaudiophonebudgets_nameservice', 'apiaudiophonebudgets_client_name', 'apiaudiophonebudgets_client_email', 'apiaudiophonebudgets_client_phone', 'apiaudiophonebudgets_client_social', 'apiaudiophonebudgets_total_price', 'apiaudiophonebudgets_url', 'apiaudiophonebudgets_status','created_at')->whereBetween('apiaudiophonebudgets_id', [1, 15])->orderBy('apiaudiophonebudgets_id','asc')->get();

						return $this->successResponseApiaudiophoneBudget(true, 200, $bd_budget_total, $apiaudiophonebudgetdata);
											
						// :::: Cuando enviamos a partir de tres parámetros en el request :::: //
					}
				}elseif($parameters_total >= 3){

					
					return $this->errorResponse('Excede los parámetros del request', 400);
				}else{

					
					return $this->errorResponse('Ha realizado una petición incorrecta', 400);
				}					
			}
		}else{

			return $this->errorResponse('Usuario no autorizado para consultar items', 401);
		}
	}

    
    /**
	 * Create ApiaudiophoneBudgets Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/
    public function createApiaudiophoneBudget($id_apiaudiophoneusers = null)
	{

		// :::: USAMOS EL CREATE PARA MANDAR A LA VISTA EL NOMBRE DEL SERVICIO :::: //

        $bdbudget_total = ApiAudiophoneBudget::count();

        // :::: Contamos el numero de items creados en la base de datos :::: //

        $items_bd_total = ApiAudiophoneItem::count();

        // :::: Obtenemos el rol de usuario :::: //

        $user = ApiAudiophoneUser::budgetuser($id_apiaudiophoneusers)->firstOrFail();

		$user_budget_rol = $user->apiaudiophoneusers_role;


        switch ($user_budget_rol) 
        {
        	
        	case ('ADMIN_ROLE'):       	

		        //:::: Validamos si existen o no presupuestos creados en la base de datos :::://

		        if($bdbudget_total == 0 && $items_bd_total >= 1){ 

					// :::: Obtenemos los items creados en la base de datos :::: //

			        $items_bd = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price')->where('apiaudiophoneitems_status', 'ACTIVO')->get();

		        	// :::: Obtenemos las instancias de los servicios :::: //

		     	   	$servicio_uno = ApiAudiophoneService::where('apiaudiophoneservices_id', 1)->firstOrFail();
		     	   	
		     	   	$servicio_dos = ApiAudiophoneService::where('apiaudiophoneservices_id', 2)->firstOrFail();


		     	   	// :::: Devolvemos los nombres y los id del servicio para mostrarlos en la vista del budget :::: //

		     	   	$servicio_uno_nombre = $servicio_uno->apiaudiophoneservices_name;
		     	   	
		     	   	$servicio_dos_nombre = $servicio_dos->apiaudiophoneservices_name;
		     	   	
		     	   	$servicio_uno_id = $servicio_uno->apiaudiophoneservices_id;
		     	   	
		     	   	$servicio_dos_id = $servicio_dos->apiaudiophoneservices_id;


		     	   	// :::: Retornamos informacíón de los servicios para que sean cargados en la vista :::: //

		     	   	return $this->errorResponseBudgetCreateUno(true, 404, 'No Existen registros de presupuesto generados, debe crearlo', $servicio_uno_nombre, $servicio_uno_id, $servicio_dos_nombre, $servicio_dos_id, $items_bd);
			    }elseif ($bdbudget_total >= 1 && $items_bd_total >= 1) {
			    

			    	// :::: Obtenemos los items creados en la base de datos :::: //

			        $items_bd = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price')->where('apiaudiophoneitems_status', 'ACTIVO')->get();

			    	// :::: Obtenemos las instancias de los servicios :::: //

			    	$servicio_uno = ApiAudiophoneService::where('apiaudiophoneservices_id', 1)->firstOrFail();
		     	   	
		     	   	$servicio_dos = ApiAudiophoneService::where('apiaudiophoneservices_id', 2)->firstOrFail();
		     	   	
		     	   	
		     	   	// :::: Devolvemos los nombres y los id del servicio para mostrarlos en la vista del budget :::: //

		     	   	$servicio_uno_nombre = $servicio_uno->apiaudiophoneservices_name;
		     	   	
		     	   	$servicio_dos_nombre = $servicio_dos->apiaudiophoneservices_name;
		     	   	
		     	   	$servicio_uno_id = $servicio_uno->apiaudiophoneservices_id;
		     	   	
		     	   	$servicio_dos_id = $servicio_dos->apiaudiophoneservices_id;
		     	   	

		     	   	// :::: Retornamos informacíón de los servicios para que sean cargados en la vista :::: //

		     	   	return $this->errorResponseBudgetCreateUno(true, 200, 'Ya Existen registros de presupuestos generados, debe crear uno para el cliente actual', $servicio_uno_nombre, $servicio_uno_id, $servicio_dos_nombre, $servicio_dos_id, $items_bd);
			    }else{

			    	// :::: Retornamos que no existen items ni presupuestos creados en la base de datos :::: //

			    	return $this->errorResponse('No existen items ni presupuestos en la base de datos', 404);
			    }
        	break;

        	default:

        	return $this->errorResponse('Usuario No Autorizado para generar presupuestos', 401);	
        }
	}

	/**
	 * store ApiaudiophoneBudgets Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/
	public function storeApiaudiophoneBudget(Request $request, $id_apiaudiophoneusers = null)
	{

		// :::: Validación del request :::: //

		$this->validate($request, [

			'apiaudiophonebudgets_id_service' => 'required|numeric|between:1,2',
			'apiaudiophonebudgets_nameservice' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_client_name' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_client_email' => 'required|email|min:1|max:60',
			'apiaudiophonebudgets_client_phone' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_client_social' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_total_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
			'apiaudiophonebudgets_status' => 'required|regex:([A-Z])',
			'apiaudiophonebudgets_items' => 'required|array',
			'apiaudiophonebudgets_items.*' => 'required|array',
			'apiaudiophonebudgets_items.*.apiaudiophonebudgets_items_quantity' => 'required|numeric',
			'apiaudiophonebudgets_items.*.apiaudiophonebudgets_items_description' => 'required|string',
			'apiaudiophonebudgets_items.*.apiaudiophonebudgets_items_unit_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
			'apiaudiophonebudgets_items.*.apiaudiophonebudgets_items_subtotal' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
		]);


		// :::: Capturamos la data que viene el request :::: //

		$apiaudiophonebudgets_data = $request->all();

		// :::: Obtenemos el usuario que realiza el store o genera el budget y accedemos al rol :::: //

		$user = ApiAudiophoneUser::budgetuser($id_apiaudiophoneusers)->firstOrFail();

		$user_budget_rol = $user->apiaudiophoneusers_role;
	
		// :::: Procesamos la creación del Budget y mandamos los datos a la vista del pdf :::: //

		if($user_budget_rol == 'ADMIN_ROLE'){
			
			
			$apiaudiophonebudgetsnew = new ApiAudiophoneBudget;
			
			$apiaudiophonebudgetsnew->id_apiaudiophoneusers = $id_apiaudiophoneusers;			
			
			$apiaudiophonebudgetsnew->id_apiaudiophoneservices = $apiaudiophonebudgets_data['apiaudiophonebudgets_id_service'];			
			$apiaudiophonebudgetsnew->apiaudiophonebudgets_nameservice = $apiaudiophonebudgets_data['apiaudiophonebudgets_nameservice'];			
			$apiaudiophonebudgetsnew->apiaudiophonebudgets_client_name = $apiaudiophonebudgets_data['apiaudiophonebudgets_client_name'];			
			
			$apiaudiophonebudgetsnew->apiaudiophonebudgets_client_email = $apiaudiophonebudgets_data['apiaudiophonebudgets_client_email'];			
			
			$apiaudiophonebudgetsnew->apiaudiophonebudgets_client_phone = $apiaudiophonebudgets_data['apiaudiophonebudgets_client_phone'];			
			
			$apiaudiophonebudgetsnew->apiaudiophonebudgets_client_social = $apiaudiophonebudgets_data['apiaudiophonebudgets_client_social'];			
			
			$apiaudiophonebudgetsnew->apiaudiophonebudgets_total_price = $apiaudiophonebudgets_data['apiaudiophonebudgets_total_price'];			
			
			$apiaudiophonebudgetsnew->apiaudiophonebudgets_status = $apiaudiophonebudgets_data['apiaudiophonebudgets_status'];						

			$apiaudiophonebudgetsnew->save();			
			

			// :::: Consultamos el ultimo Budget creado en la tabla para obtener el ID y armar el nombdre del PDF :::: //

			$apiaudiophonebudgetsnew_saved = ApiAudiophoneBudget::latest()->first();		

			$id_budget_pdf = $apiaudiophonebudgetsnew_saved->apiaudiophonebudgets_id;
			

			// :::: Mandamos el request para salvar el presupuesto en el servidor y obtener el link :::: //

			$link = $this->saveBudgetPdf($apiaudiophonebudgets_data, $id_budget_pdf);
	      		
			$apiaudiophonebudgetsnew_saved->apiaudiophonebudgets_url = $link;

      		$apiaudiophonebudgetsnew_saved->save();
      		

      		return $this->successResponseApiaudiophoneBudgetStore(true, 201, 'Budget Creado Satisfactoriamente', $apiaudiophonebudgetsnew_saved);

		}else{

			return $this->errorResponse('Usuario no autorizado para crear presupuestos', 401);
		}
	}

	/**
	 * update ApiaudiophoneBudgest instance
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/
	public function updateApiaudiophoneBudget(Request $request, $id_apiaudiophoneusers = null)
	{

		// :::: Validación del request, para esta actualización solo se tomarán en cuenta los campos de la tabla budgets :::: //

		$this->validate($request, [

			'apiaudiophonebudgets_id' => 'required|numeric',
			'apiaudiophonebudgets_id_service' => 'required|numeric|between:1,2',
			'apiaudiophonebudgets_nameservice' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_client_name' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_client_email' => 'required|email|min:1|max:60',
			'apiaudiophonebudgets_client_phone' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_client_social' => 'required|string|min:1|max:60',
			'apiaudiophonebudgets_total_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
		]);


		// :::: obetenemos los datos provenientes del request :::: //

		$budget_data_update = $request->all();

		// :::: Obtenemos el rol de usuario :::: //

        $user = ApiAudiophoneUser::budgetuser($id_apiaudiophoneusers)->firstOrFail();

		$user_budget_rol = $user->apiaudiophoneusers_role;

		
		if($user_budget_rol == 'ADMIN_ROLE'){


			$apiaudiophonebudgetupdate = ApiAudiophoneBudget::where('apiaudiophonebudgets_id', $budget_data_update['apiaudiophonebudgets_id'])->update([

				'id_apiaudiophoneusers' => $id_apiaudiophoneusers,
				'id_apiaudiophoneservices' => $budget_data_update['apiaudiophonebudgets_id_service'],
				'apiaudiophonebudgets_nameservice' => $budget_data_update['apiaudiophonebudgets_nameservice'],
				'apiaudiophonebudgets_client_name' => $budget_data_update['apiaudiophonebudgets_client_name'],
				'apiaudiophonebudgets_client_email' => $budget_data_update['apiaudiophonebudgets_client_email'],
				'apiaudiophonebudgets_client_phone' => $budget_data_update['apiaudiophonebudgets_client_phone'],
				'apiaudiophonebudgets_client_social' => $budget_data_update['apiaudiophonebudgets_client_social'],
				'apiaudiophonebudgets_total_price' => $budget_data_update['apiaudiophonebudgets_total_price']
			]);


			$apiaudiophonebudgetupdated = ApiAudiophoneBudget::where('apiaudiophonebudgets_id', $budget_data_update['apiaudiophonebudgets_id'])->first();


			return $this->successResponseApiaudiophoneBudgetUpdate(true, 201, 'Budget Actualizdo Satisfactoriamente', $apiaudiophonebudgetupdated);
		}else{

			return $this->errorResponse('Usuario no autorizado para actualizar presupuestos', 401);
		}
	}


	/**
	 * update ApiaudiophoneBudgestStatus instance
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/
	public function updateApiaudiophoneBudgetStatus(Request $request, $id_apiaudiophoneusers = null)
	{

		// :::: Validación del request, para esta actualización solo se recibe el id y el nuevo status :::: //

		$this->validate($request, [

			'apiaudiophonebudgets_id' => 'required|numeric',
			'apiaudiophonebudgets_status' => 'required|regex:([A-Z])'
		]);


		// :::: obetenemos los datos provenientes del request :::: //

		$budget_data_update_status = $request->all();

		// :::: Obtenemos el rol y status del usuario :::: //

        $user = ApiAudiophoneUser::budgetuser($id_apiaudiophoneusers)->firstOrFail();

		$user_budget_rol = $user->apiaudiophoneusers_role;

		$user_budget_status = $user->apiaudiophoneusers_status;

		
		if(($user_budget_rol == 'ADMIN_ROLE') && ($user_budget_status == true)){

			
			// :::: Obtenemos el Budget para actualizar el estus del mismo :::: //

			$budget_update_status = ApiAudiophoneBudget::findOrFail($budget_data_update_status['apiaudiophonebudgets_id']);

			// :::: Evaluamos el estatus del budget y actualizamos :::: //

			switch($budget_data_update_status['apiaudiophonebudgets_status']){

				case('PAGADO'):

					$budget_update_status->apiaudiophonebudgets_status = $budget_data_update_status['apiaudiophonebudgets_status'];
				break;

				case('NO_APLICA'):

					$budget_update_status->apiaudiophonebudgets_status = $budget_data_update_status['apiaudiophonebudgets_status'];
				break;

				default:

				return $this->errorResponse('El estatus '.$budget_data_update_status['apiaudiophonebudgets_status'].' no esta permitido para ser almacenado', 422);
			}


			$budget_update_status->update();

			return $this->successResponseApiaudiophoneBudgetStore(true, 201, 'Estado del Budget Actualizdo Satisfactoriamente', $budget_update_status);
		}else{

			
			return $this->errorResponse('Usuario no autorizado para actualizar Estatus del Budget', 401);
		}
	}	


	/**
	 * destroy ApiaudiophoneBudgets Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/
	public function destroyApiaudiophoneBudget(Request $request, $id_apiaudiophoneusers = null)
	{

		//:::: Validación del request :::://

		$this->validate($request, [

			'apiaudiophonebudgets_id' => 'required|numeric'
		]);

		
		// :::: obetenemos los datos provenientes del request :::: //

		$budget_data_destroy = $request->all();

		// :::: Obtenemos el usuario que realiza el destroy del budget y accedemos al rol :::: //

		$user = ApiAudiophoneUser::budgetuser($id_apiaudiophoneusers)->firstOrFail();

		$user_budget_rol = $user->apiaudiophoneusers_role;

		
		if($user_budget_rol == 'ADMIN_ROLE'){


			$apiaudiophonebudgetdelete = ApiAudiophoneBudget::where('apiaudiophonebudgets_id', $budget_data_destroy['apiaudiophonebudgets_id'])->delete();

			return $this->errorResponseApiaudiophoneBudgetDelete(true, 201, 'Budget Eliminado Satisfactoriamente');			
		}else{

			return $this->errorResponse('Usuario no autorizado para eliminar items', 401);
		}
	}


	// ::: Retorna Keys del Request ::::  //

	public function arrayKeysRequest(array $request_array){


		$array_keys = array_keys($request_array);

		return $array_keys;
	}


	public function saveBudgetPdf(array $request_array_store, $pdf_id = null){

		//separador del direc

		define('DS', DIRECTORY_SEPARATOR);

		// :::: obtenemos el día de generación del presupuesto :::: //

		$today = Carbon::today('America/Caracas')->format('Y-m-d');

		// :::: Generamos el nombre del presupuesto :::: //

		$nombre_pdf = 'psp_'.$pdf_id.'_'.$request_array_store['apiaudiophonebudgets_client_name'].'_'.$today.'.pdf';

		// ::: Definimos el nombre de la carpeta si no existe en el server :::: //

		$carpeta = str_replace('\\', DS, strstr($_SERVER['DOCUMENT_ROOT'], 'apiaudiophone\public', true).'appdocs\\');
				
		// :::: Verificamos carpeta, si no existe,  creamos con permisos 777 :::: //

		if(!file_exists($carpeta)){

			mkdir($carpeta, 0777, true);
		}
		
		// :::: Generamos la ruta del presupuesto donde será almacenado el documento :::: //

		$url = $carpeta.$nombre_pdf;


		// :::: Armamamos los valores que vamos a mandar a la vista del Presupuesto :::: //

		$items = $request_array_store['apiaudiophonebudgets_items'];

		$totals = $request_array_store['apiaudiophonebudgets_total_price'];
		
		$names = $request_array_store['apiaudiophonebudgets_client_name'];
		
		$emails = $request_array_store['apiaudiophonebudgets_client_email'];
		
		$phones = $request_array_store['apiaudiophonebudgets_client_phone'];
		
		$networks = $request_array_store['apiaudiophonebudgets_client_social'];

		$services = $request_array_store['apiaudiophonebudgets_nameservice'];


		// :::: Cargamos la vista y mandamos los datos del presupuesto :::: //


		$pdf = PDF::loadView('budgetview.presupuesto', 
			[
			'items' => $items, 
		 	'totals' => $totals,
		 	'names' => $names,
		 	'emails' => $emails,
		 	'phones' => $phones,
		 	'networks' => $networks,
		 	'todays' => $today,
		 	'ids' => $pdf_id,
		 	'services' => $services						
			]
		)->save($url);		

		return $url;
	}
}
