<?php

namespace App\Http\Controllers\Apiaudiophonecontrollers;

use App\Traits\ApiResponserTrait;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Dusterio\LumenPassport\Http\Controllers\AccessTokenController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ApiAudiophoneUserController extends Controller
{
    use ApiResponserTrait;

	/**
     *  show ApiAudiophoneUser instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showApiAudiophoneUser(Request $request)
    {
    	// :::::: Validacion de parametros del request ::::::
    	$this->validate($request, [

    		'start' => 'numeric',
    		'end' => 'numeric',
            'stringsearch' => 'string'
    	]);

    	// :::::: Total de elementos del Request ::::::
    	$parameterstotal = count($request->all());

    	// :::::: Obtenemos total de usuarios registrados ::::::
    	$bduserstotal = ApiAudiophoneUser::count();

        // :::::: Cuando se realiza una consulta por string search
        if(($parameterstotal > 0 && $parameterstotal < 2) && ($bduserstotal > 0) && ($request->stringsearch)){

            //obtenemos string de consulta
            $stringsearch = $request->input('stringsearch');

            if(empty($stringsearch)){

                $apiaudiophoneuserdata = ApiAudiophoneUser::select('apiaudiophoneusers_id','apiaudiophoneusers_fullname', 'apiaudiophoneusers_email', 'apiaudiophoneusers_role', 'apiaudiophoneusers_status', 'created_at')
                ->whereBetween('apiaudiophoneusers_id', [1, 15])
                ->orderBy('apiaudiophoneusers_id', 'desc')
                ->get();

               
                return $this->successResponseApiaudiophoneUser(true, 200, $bduserstotal, $apiaudiophoneuserdata);
            }else{

                //Contamos la cantidad de usuarios que se generan en la consulta por like para paginación en front
                $apiaudiophoneusercount = DB::table('apiaudiophoneusers')
                ->where('apiaudiophoneusers_fullname', 'like', '%'.$stringsearch.'%')
                ->orWhere('apiaudiophoneusers_email', 'like', '%'.$stringsearch.'%')
                ->count();

                //Consultamos en la base de datos cuando hacemos busqueda por string y capturamos la data
                $apiaudiophoneuserdata = ApiAudiophoneUser::select('apiaudiophoneusers_id','apiaudiophoneusers_fullname', 'apiaudiophoneusers_email', 'apiaudiophoneusers_role', 'apiaudiophoneusers_status', 'created_at')
                ->where('apiaudiophoneusers_fullname', 'like', '%'.$stringsearch.'%')
                ->orWhere('apiaudiophoneusers_email', 'like', '%'.$stringsearch.'%')
                ->get();


                return $this->successResponseApiaudiophoneUserCount(true, 200, $bduserstotal, $apiaudiophoneusercount, $apiaudiophoneuserdata);               
            }
    	// :::::: Cuando hay dos parametros en el request y existan usuarios en la base de datos
    	}elseif(($parameterstotal > 0 && $parameterstotal < 3) && $bduserstotal > 0){

    		//Obtenemos inicio y fin de la consulta
    		$start = $request->input('start');
    		$end = $request->input('end');

    		//Si los parametros vienen en cero o nulos hacemos consulta de los primeros 5
    		if((empty($start)) && (empty($end))){


	    		//Consultamos en la base de datos cuando el request no manda parametros (primera consulta)
	    		$apiaudiophoneuserdata = ApiAudiophoneUser::select('apiaudiophoneusers_id','apiaudiophoneusers_fullname', 'apiaudiophoneusers_email', 'apiaudiophoneusers_role', 'apiaudiophoneusers_status', 'created_at')
	    		->whereBetween('apiaudiophoneusers_id', [1, 15])
	    		->orderBy('apiaudiophoneusers_id', 'desc')
	    		->get();
    		}else{

	    		//Consultamos usuarios en la base de datos para mandarlos a la vista
	    		$apiaudiophoneuserdata = ApiAudiophoneUser::select('apiaudiophoneusers_id', 'apiaudiophoneusers_fullname', 'apiaudiophoneusers_email', 'apiaudiophoneusers_role', 'apiaudiophoneusers_status', 'created_at')
	    		->whereBetween('apiaudiophoneusers_id', [$start, $end])
	    		->orderBy('apiaudiophoneusers_id', 'desc')
	    		->get();
	    	}

    		//Retornamos Json con la consulta realizada, total paginacion 3(version de prueba)
    		
            return $this->successResponseApiaudiophoneUser(true, 200, $bduserstotal, $apiaudiophoneuserdata);

    		// :::: Cuando no hay parametros de consulta y existen usuarios en la BD :::::
    	}elseif($parameterstotal == 0 && $bduserstotal > 0){

    		//Consultamos en la base de datos cuando el request no manda parametros (primera consulta)
    		$apiaudiophoneuserdata = ApiAudiophoneUser::select('apiaudiophoneusers_id', 'apiaudiophoneusers_fullname', 'apiaudiophoneusers_email', 'apiaudiophoneusers_role', 'apiaudiophoneusers_status', 'created_at')
    		->whereBetween('apiaudiophoneusers_id', [1, 15])
    		->orderBy('apiaudiophoneusers_id', 'desc')
    		->get();

    		//Retornamos Json con la consulta realizada, total paginacion 5
    		
            return $this->successResponseApiaudiophoneUser(true, 200, $bduserstotal, $apiaudiophoneuserdata);

    		//Cuando hay parametros de consulta pero no hay usuarios en la base de datos
    	}elseif(($parameterstotal > 0 && $parameterstotal < 3) && $bduserstotal == 0){

    		//Retornamos Json con mensaje de error
    	    
            return $this->errorResponseApiaudiophoneUser(true, 404, $bduserstotal, 'No existen usuarios registrados en la base de datos');

    		//Cuando no hay parametros ni usuarios en la base de datos
    	}else{

    		//Retornamos Json con mensaje de error
    		
            return $this->errorResponseApiaudiophoneUser(true, 400, $bduserstotal, 'Ha realizado una peticion incorrecta');  
    	}
    }

    /**
     * new store ApiAudiophoneUser instance.
     *
     * @param \Illuminate\Http\Request $request
     *@return \Illuminate\Http\Response
     */
    public function storeApiAudiophoneUser(Request $request)
    {

        $this->validate($request, [

            'apiaudiophoneusers_fullname' => 'required|string|max:60',
            'apiaudiophoneusers_email' => 'required|email|unique:apiaudiophoneusers,apiaudiophoneusers_email',
            'apiaudiophoneusers_password' => 'required|string'
        ]);

    	$apiaudiophoneuserdata = $request->all();

    	$apiaudiophoneusernew = new ApiAudiophoneUser;

    	$apiaudiophoneusernew->apiaudiophoneusers_fullname = $apiaudiophoneuserdata['apiaudiophoneusers_fullname'];
        $apiaudiophoneusernew->apiaudiophoneusers_email = $apiaudiophoneuserdata['apiaudiophoneusers_email'];
        $apiaudiophoneusernew->apiaudiophoneusers_password = app('hash')->make($apiaudiophoneuserdata['apiaudiophoneusers_password']);

        $apiaudiophoneusernew->save();

        return $this->successResponseApiaudiophoneUserStore(true, 201, 'Usuario Creado Exitosamente', $apiaudiophoneusernew);
    }

    /**
     * update ApiAudiophoneUser instance.
     *
     * @param \Illuminate\Http\Request $request
     *@return \Illuminate\Http\Response
     */
    public function updateApiAudiophoneUser(Request $request, $apiaudiophoneusers_id)
    {


        $this->validate($request, [

            'apiaudiophoneusers_fullname' => 'string|max:60',
            'apiaudiophoneusers_email' => 'email',
            'apiaudiophoneusers_password' => 'string',
            'apiaudiophoneusers_role' =>'string'
        ]);

        // dd( $request );
        //obtenemos los parametros del request, ninguno de ellos es requerido(por ahora)
    	$apiaudiophoneuserdata = $request->all();

        // :::::: Total de elementos del Request ::::::
        $parameterstotal = count($request->all());

    	$apiaudiophoneuserupdate = ApiAudiophoneUser::findOrFail($apiaudiophoneusers_id);

        //cuando el usuario administrador cambia el role a un usuario.
        if($parameterstotal == 1 && ($request->apiaudiophoneusers_role)){

            $apiaudiophoneuserupdate->apiaudiophoneusers_role = $apiaudiophoneuserdata['apiaudiophoneusers_role'];

            //cuando un usuario acutaliza su contraseña
        }elseif($parameterstotal  == 3 && ($request->apiaudiophoneusers_password)){


            //nos aseguramos de eliminar el rol para evitar actualizarlo en esta sección
            unset($apiaudiophoneuserdata['apiaudiophoneusers_role']);

            $apiaudiophoneuserupdate->apiaudiophoneusers_fullname = $apiaudiophoneuserdata['apiaudiophoneusers_fullname'];
            $apiaudiophoneuserupdate->apiaudiophoneusers_email = $apiaudiophoneuserdata['apiaudiophoneusers_email'];
            $apiaudiophoneuserupdate->apiaudiophoneusers_password = app('hash')->make($apiaudiophoneuserdata['apiaudiophoneusers_password']);

            //cuando un usuario actualiza el nombre o el email
        }else{

            //nos aseguramos de eliminar el rol y el password de esta validación
            unset($apiaudiophoneuserdata['apiaudiophoneusers_role'], $apiaudiophoneuserdata['apiaudiophoneusers_password']);

            $apiaudiophoneuserupdate->apiaudiophoneusers_fullname = $apiaudiophoneuserdata['apiaudiophoneusers_fullname'];
            $apiaudiophoneuserupdate->apiaudiophoneusers_email = $apiaudiophoneuserdata['apiaudiophoneusers_email'];
        }

        // no se necesita pasarle parametros ya que se modifican los atributos el objeto
        $apiaudiophoneuserupdate->update();

    	return $this->successResponseApiaudiophoneUserStore(true, 201, 'Usuario Actualizado Exitosamente', $apiaudiophoneuserupdate);
    }

    /**
     * inactive ApiAudiophoneUser instance.
     *
     * @param \Illuminate\Http\Request $request
     *@return \Illuminate\Http\Response
     */
    public function inactiveApiAudiophoneUser(Request $request, $apiaudiophoneusers_id)
    {

        $this->validate($request, [

            'apiaudiophoneusers_status' => 'required|boolean'
        ]);

        $apiaudiophoneuserdata = $request->all();

        $apiaudiophoneuserinactive = ApiAudiophoneUser::findOrFail($apiaudiophoneusers_id);


        if($request->apiaudiophoneusers_status == false){

            $apiaudiophoneuserinactive->apiaudiophoneusers_status = $apiaudiophoneuserdata['apiaudiophoneusers_status'];

            $apiaudiophoneuserinactive->update();
            

            return $this->successResponseApiaudiophoneUserStore(true, 201, 'Usuario Inactivado Exitosamente', $apiaudiophoneuserinactive);
        }else{

            
            return $this->errorResponseApiaudiophoneUserUpdate(true, 422, 'No se ha Inactivado el Usuario', $apiaudiophoneuserinactive);
        }
    }

    /**
     * activate ApiAudiophoneUser instance.
     *
     * @param \Illuminate\Http\Request $request
     *@return \Illuminate\Http\Response
     */
    public function activateApiAudiophoneUser(Request $request, $apiaudiophoneusers_id)
    {

        $this->validate($request, [

            'apiaudiophoneusers_status' => 'required|boolean'
        ]);

        $apiaudiophoneuserdata = $request->all();

        $apiaudiophoneuseractivate = ApiAudiophoneUser::findOrFail($apiaudiophoneusers_id);

        if($request->apiaudiophoneusers_status == true){

            $apiaudiophoneuseractivate->apiaudiophoneusers_status = $apiaudiophoneuserdata['apiaudiophoneusers_status'];

            $apiaudiophoneuseractivate->update();

            
            return $this->successResponseApiaudiophoneUserStore(true, 201, 'Usuario Reactivado Exitosamente', $apiaudiophoneuseractivate);
        }else{

            return $this->errorResponseApiaudiophoneUserUpdate(true, 422, 'No se ha Reactivado el Usuario', $apiaudiophoneuserinactive);
        }
    }

	/**
     * destroy ApiAudiophoneUser instance.
     *
     * @param \Illuminate\Http\Request $request
     *@return \Illuminate\Http\Response
     */
    public function destroyApiAudiophoneUser($apiaudiophoneusers_id)
    {

       	$apiaudiophoneuserdestroy = ApiAudiophoneUser::findOrFail($apiaudiophoneusers_id);

       	$apiaudiophoneuserdestroy->delete();

    	return $this->errorResponseApiaudiophoneUserDestroy(true, 200, 'Usuario Eliminado Satisfactoriamente');
    }
}

