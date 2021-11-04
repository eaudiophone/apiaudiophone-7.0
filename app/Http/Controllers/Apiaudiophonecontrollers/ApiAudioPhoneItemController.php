<?php

namespace App\Http\Controllers\Apiaudiophonecontrollers;

use App\Traits\ApiResponserTrait;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use App\Apiaudiophonemodels\ApiAudiophoneItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiAudioPhoneItemController extends Controller
{
    use ApiResponserTrait;


    /**
	 * show ApiaudiophoneItems Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/

	public function showApiaudiophoneItem(Request $request, $id_apiaudiophoneusers = null)
	{

		//:::: Validación del request :::://

		$this->validate($request, [

			'start' => 'numeric',
			'end'   => 'numeric',
			'stringsearch' => 'string'
		]);


		//:::: Capturamos el contenido del request :::://

		$item_data_show = $request->all();
		
		//:::: Contamos elementos del request :::://

		$parameters_total = count($item_data_show);

		//:::: Contamos los registros de la tabal Items :::://

		$bd_item_total = ApiAudiophoneItem::count();

		//:::: Obtenemos el usuario que gestiona el item y accedemos al rol:::://

		$user = ApiAudiophoneUser::itemuser($id_apiaudiophoneusers)->first();

		$user_item_rol = $user->apiaudiophoneusers_role;

		
		if($user_item_rol == 'ADMIN_ROLE'){

			switch($bd_item_total){

				case(0):

					return $this->errorResponse('No Existen Items, debe crearlos', 404);

					break;
				default:

				// :::: Aplicamos misma lógica del show user :::: //

				if(($parameters_total == 1) && (key($item_data_show) == 'stringsearch')){

					$chain = $item_data_show['stringsearch'];

					// :::: Cuando es la primera consulta, la cadena el request esta vacía y hay menos o igual a 15 items :::: //

					if(!($chain) && ($bd_item_total <= 15)){						
					
						// :::: Eviamos los Items creados a la vista :::: //

						$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->orderBy('apiaudiophoneitems_id','asc')->get();

						return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);

						
						// :::: Cuando es la primera consulta, la cadena el request tiene un espacio en blanco y existen menos o igual a 15 items :::: //
					}elseif((ctype_space($chain) == true) && ($bd_item_total <= 15)){


						// :::: Eviamos los Items creados a la vista :::: //

						$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->orderBy('apiaudiophoneitems_id','asc')->get();


						return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);
					

						// :::: Cuando es la primera consulta, la cadena el request tiene un espacio en blanco y existen mas de 15 items :::: //
					//}elseif((ctype_space($chain) == true) && ($bd_item_total >= 15)){
					}elseif((ctype_space($chain) == true) && ($bd_item_total >= 15)){


						// :::: Eviamos los Items creados a la vista :::: //

						$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->whereBetween('apiaudiophoneitems_id', [1, 15])->orderBy('apiaudiophoneitems_id','asc')->get();


						return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);
					
						// :::: Cuando stringsearch está vacía y asumimos que hay mas de 15 items :::: //
					}elseif(!($chain) && ($bd_item_total >= 15)){

						// :::: Eviamos los Items creados a la vista :::: //

						$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->whereBetween('apiaudiophoneitems_id', [1, 15])->orderBy('apiaudiophoneitems_id','asc')->get();


						return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);
					
						// :::: Cuando existe una busqueda por stringsearch asumimos que hay mas de 5 usuarios :::: //
					}else{
					
						// :::: Contamos los Elementos que se obtienen para busqueda stringsearch :::: //

						$apiaudiophoneitemdatacount = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->where('apiaudiophoneitems_name', 'like', '%'.$chain.'%')->orWhere('apiaudiophoneitems_description', 'like', '%'.$chain.'%')->count();

						// :::: Eviamos los Items creados a la vista :::: //

						$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->where('apiaudiophoneitems_name', 'like', '%'.$chain.'%')->orWhere('apiaudiophoneitems_description', 'like', '%'.$chain.'%')->orderBy('apiaudiophoneitems_id', 'asc')->get();


						
						return $this->successResponseApiaudiophoneItem(true, 200, $apiaudiophoneitemdatacount, $apiaudiophoneitemdata); 
					}
				}elseif($parameters_total == 2){

					// :::: Obtenemos las Key del request :::: //

					$keys_item_data_show = $this->arrayKeysRequest($item_data_show);

					// :::: Validamos que lo recibido por el Request sean los parametros de star y end :::: //

					if(($keys_item_data_show[0] == 'start') && ($keys_item_data_show[1] == 'end')){

						$start = $item_data_show['start'];
						$end = $item_data_show['end'];

						// :::: Cuando están vacíos los parametros de búsqueda, devuelve los primeros 5 :::: //

						if(!($start) && !($end)){
						
							// :::: Eviamos los Items creados a la vista :::: //

							$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->whereBetween('apiaudiophoneitems_id', [1, 15])->orderBy('apiaudiophoneitems_id','asc')->get();


							return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);

							// :::: Cuando está vacío uno de los parametros de búsqueda, devuelve los primeros 5:::: //

						}elseif(!($start) || !($end)){
						
							// :::: Eviamos los Items creados a la vista :::: //

							$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->whereBetween('apiaudiophoneitems_id', [1, 15])->orderBy('apiaudiophoneitems_id','asc')->get();


							return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);					
						}else{
						
							// :::: Eviamos los Items creados a la vista :::: //

							$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->whereBetween('apiaudiophoneitems_id', [$start, $end])->orderBy('apiaudiophoneitems_id','asc')->get();

							return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);
						}
					}else{


						return $this->errorResponse('Elementos del Request no Corresponden', 400);
					}

					// :::: Primera consulta hay 15 y menos de 15 items creados en la BD :::: //					
				}elseif($parameters_total == 0){

					if($bd_item_total <= 15){						

						$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->orderBy('apiaudiophoneitems_id','asc')->get();

						return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);

						// :::: Cuando es la primera consulta, sin parametros y hay mas de 5 items :::: //
					}else{							

						// :::: Enviamos los Items creados a la vista :::: //

						$apiaudiophoneitemdata = ApiAudiophoneItem::select('apiaudiophoneitems_id', 'id_apiaudiophoneusers', 'apiaudiophoneitems_name', 'apiaudiophoneitems_description', 'apiaudiophoneitems_price', 'apiaudiophoneitems_status')->whereBetween('apiaudiophoneitems_id', [1, 15])->orderBy('apiaudiophoneitems_id','asc')->get();

						return $this->successResponseApiaudiophoneItem(true, 200, $bd_item_total, $apiaudiophoneitemdata);
											
						// :::: Cuando enviamos a partir de tres parámetros en el request :::: //
					}
				}elseif($parameters_total >= 3){

					
					return $this->errorResponse('Excede los parámetros del Request', 400);
				}else{

					
					return $this->errorResponse('Ha realizado una peticion incorrecta', 400);
				}					
			}
		}else{

			return $this->errorResponse('Usuario no autorizado para consultar items', 401);
		}
	}


	/**
	 * store ApiaudiophoneItems Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/

	public function storeApiaudiophoneItem(Request $request, $id_apiaudiophoneusers = null)
	{

		//:::: Validación del request :::://

		$this->validate($request, [

			'apiaudiophoneitems_name' => 'required|string|min:1|max:60',
			'apiaudiophoneitems_description' => 'required|string|min:1|max:60',
			'apiaudiophoneitems_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
			'apiaudiophoneitems_status' => 'required|regex:([A-Z])',
		]);

		
		// :::: obetenemos los datos provenientes del request :::: //

		$item_data_store = $request->all();

		//:::: Obtenemos el usuario que gestiona el item y accedemos al rol:::://

		$user = ApiAudiophoneUser::itemuser($id_apiaudiophoneusers)->firstOrFail();

		$user_item_rol = $user->apiaudiophoneusers_role;

		
		if($user_item_rol == 'ADMIN_ROLE'){

			
			$apiaudiophoneitemnew = new ApiAudiophoneItem;

			$apiaudiophoneitemnew->id_apiaudiophoneusers = $id_apiaudiophoneusers;			
			$apiaudiophoneitemnew->apiaudiophoneitems_name = $item_data_store['apiaudiophoneitems_name'];			
			$apiaudiophoneitemnew->apiaudiophoneitems_description = $item_data_store['apiaudiophoneitems_description'];			
			$apiaudiophoneitemnew->apiaudiophoneitems_price = $item_data_store['apiaudiophoneitems_price'];			
			$apiaudiophoneitemnew->apiaudiophoneitems_status = $item_data_store['apiaudiophoneitems_status'];			

			$apiaudiophoneitemnew->save();


			return $this->successResponseApiaudiophoneItemStore(true, 201, 'Item Creado Satisfactoriamente', $apiaudiophoneitemnew);

		}else{

			return $this->errorResponse('Usuario no autorizado para crear items', 401);
		}
	}


	/**
	 * update ApiaudiophoneItems Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/

	public function updateApiaudiophoneItem(Request $request, $id_apiaudiophoneusers = null)
	{

		//:::: Validación del request :::://

		$this->validate($request, [

			'apiaudiophoneitems_id' => 'required|numeric',
			'apiaudiophoneitems_name' => 'required|string|min:1|max:60',
			'apiaudiophoneitems_description' => 'required|string|min:1|max:60',
			'apiaudiophoneitems_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
		]);

		
		// :::: obetenemos los datos provenientes del request :::: //

		$item_data_update = $request->all();

		//:::: Obtenemos el usuario que gestiona el item y accedemos al rol:::://

		$user = ApiAudiophoneUser::itemuser($id_apiaudiophoneusers)->first();

		$user_item_rol = $user->apiaudiophoneusers_role;

		
		if($user_item_rol == 'ADMIN_ROLE'){


			$apiaudiophoneitemupdate = ApiAudiophoneItem::where('apiaudiophoneitems_id', $item_data_update['apiaudiophoneitems_id'])->update([

				'id_apiaudiophoneusers' => $id_apiaudiophoneusers,
				'apiaudiophoneitems_name' => $item_data_update['apiaudiophoneitems_name'],
				'apiaudiophoneitems_description' => $item_data_update['apiaudiophoneitems_description'],
				'apiaudiophoneitems_price' => $item_data_update['apiaudiophoneitems_price']
			]);


			$apiaudiophoneitemupdated = ApiAudiophoneItem::where('apiaudiophoneitems_id', $item_data_update['apiaudiophoneitems_id'])->first();


			return $this->successResponseApiaudiophoneItemUpdate(true, 201, 'Item Actualizdo Satisfactoriamente', $apiaudiophoneitemupdated);
		}else{

			return $this->errorResponse('Usuario no autorizado para actualizar items', 401);
		}
	}


	/**
	 * update ApiaudiophoneItemStatus instance
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/
	public function updateApiaudiophoneItemStatus(Request $request, $id_apiaudiophoneusers = null)
	{

		// :::: Validación del request, para esta actualización solo se recibe el id y el nuevo status :::: //

		$this->validate($request, [

			'apiaudiophoneitems_id' => 'required|numeric',
			'apiaudiophoneitems_status' => 'required|regex:([A-Z])'
		]);

		
		// :::: obetenemos los datos provenientes del request :::: //

		$item_data_update_status = $request->all();

		// :::: Obtenemos el rol y status del usuario :::: //

        $user = ApiAudiophoneUser::itemuser($id_apiaudiophoneusers)->firstOrFail();

		$user_item_rol = $user->apiaudiophoneusers_role;

		$user_item_status = $user->apiaudiophoneusers_status;

		
		if(($user_item_rol == 'ADMIN_ROLE') && ($user_item_status == true)){


			// :::: Obtenemos el Item para actualizar el estus del mismo :::: //

			$item_update_status = ApiAudiophoneItem::findOrFail($item_data_update_status['apiaudiophoneitems_id']);

			// :::: Evaluamos el estatus del item y actualizamos :::: //

			switch($item_data_update_status['apiaudiophoneitems_status']){

				case('INACTIVO'):

					$item_update_status->apiaudiophoneitems_status = $item_data_update_status['apiaudiophoneitems_status'];
				break;

				case('ACTIVO'):

					$item_update_status->apiaudiophoneitems_status = $item_data_update_status['apiaudiophoneitems_status'];
				break;

				default:

				return $this->errorResponse('El estatus '.$item_data_update_status['apiaudiophoneitems_status'].' no esta permitido para ser almacenado', 422);
			}

			
			$item_update_status->update();

			return $this->successResponseApiaudiophoneItemStore(true, 201, 'Estado del Item ha sido Actualizdo Satisfactoriamente', $item_update_status);
		}else{

			return $this->errorResponse('Usuario no autorizado para actualizar Estatus del Item', 401);
		}
	}


	/**
	 * destroy ApiaudiophoneItems Instance	
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response 
	*/

	public function destroyApiaudiophoneItem(Request $request, $id_apiaudiophoneusers = null)
	{

		//:::: Validación del request :::://

		$this->validate($request, [

			'apiaudiophoneitems_id' => 'required|numeric'
		]);

		
		// :::: obetenemos los datos provenientes del request :::: //

		$item_data_destroy = $request->all();

		//:::: Obtenemos el usuario que gestiona el item y accedemos al rol:::://

		$user = ApiAudiophoneUser::itemuser($id_apiaudiophoneusers)->first();

		$user_item_rol = $user->apiaudiophoneusers_role;

		
		if($user_item_rol == 'ADMIN_ROLE'){


			$apiaudiophoneitemdelete = ApiAudiophoneItem::where('apiaudiophoneitems_id', $item_data_destroy['apiaudiophoneitems_id'])->delete();

			return $this->errorResponseApiaudiophoneItemDelete(true, 201, 'Item Eliminado Satisfactoriamente');
			
		}else{

			return $this->errorResponse('Usuario no autorizado para eliminar items', 401);
		}
	}


	// ::: Retorna Keys del Request :::: //

	public function arrayKeysRequest(array $request_array){


		$array_keys = array_keys($request_array);

		return $array_keys;
	}
}
