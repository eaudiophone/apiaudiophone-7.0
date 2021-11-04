<?php

namespace App\Apiaudiophonemodels;

use Illuminate\Database\Eloquent\Model;
use App\Apiaudiophonemodels\ApiAudiophoneService;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use Illuminate\Database\Eloquent\SoftDeletes;


class ApiAudiophoneBudget extends Model
{
    

    use SoftDeletes;

   /**
    * The attributes should be mutatedto dates
    *
    * @var array
    */
    protected $dates = [
        'deleted_at'
    ];


    protected $table = 'apiaudiophonebudgets';

    protected $primaryKey = 'apiaudiophonebudgets_id';


	/**
     * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [

        'apiaudiophonebudgets_client_name',
        'apiaudiophonebudgets_client_email',
        'apiaudiophonebudgets_client_phone',
        'apiaudiophonebudgets_client_social',
        'apiaudiophonebudgets_url'      
    ];


    //::::: INDICAMOS QUE UN BUDGET PERTENECE A UN USUARIO ::::://

   	public function apiaudiophoneuser()
    {

    	return $this->belongsTo(ApiAudiophoneUser::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    //::::: INDICAMOS QUE UN BUDGET PERTENECE A UN SERVICIO ::::://

    public function apiaudiophoneservice()
    {

        return $this->belongsTo(ApiAudiophoneService::class, 'id_apiaudiophoneservices', 'apiaudiophoneservices_id');
    }


    //::: SCOPES ::://
    // ..... ESPACIO PARA AGREGAR LOS SCOPES QUE NECESITEMOS
}
