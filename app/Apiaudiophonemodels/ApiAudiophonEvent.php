<?php

namespace App\Apiaudiophonemodels;

use Illuminate\Database\Eloquent\Model;
use App\Apiaudiophonemodels\ApiAudiophoneService;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use App\Apiaudiophonemodels\ApiAudiophoneTerm;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiAudiophonEvent extends Model
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


    protected $table = 'apiaudiophonevents';

    protected $primaryKey = 'apiaudiophonevents_id';


	/**
     * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [

        'apiaudiophonevents_title',
        'apiaudiophonevents_address',
        'apiaudiophonevents_description',
        'apiaudiophonevents_date',
        'apiaudiophonevents_begintime',
        'apiaudiophonevents_finaltime',
        'apiaudiophonevents_totalhours'
        
    ];


    //::::: INDICAMOS QUE UN EVENTO PERTENECE A UN USUARIO ::::://

   	public function apiaudiophoneuser()
    {

    	return $this->belongsTo(ApiAudiophoneUser::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    //::::: INDICAMOS QUE UN EVENTO PERTENECE A UN SERVICIO ::::://

    public function apiaudiophoneservice()
    {

        return $this->belongsTo(ApiAudiophoneService::class, 'id_apiaudiophoneservices', 'apiaudiophoneservices_id');
    }


    //::::: INDICAMOS QUE UN EVENTO ES CONDICIONADO POR UN TERM ::::://

    public function apiaudiophoneterm()
    {

        return $this->belongsTo(ApiAudiophoneTerm::class, 'id_apiaudiophoneterms', 'apiaudiophoneterms_id');
    }


    //::: SCOPES ::://
    // ..... ESPACIO PARA AGREGAR LOS SCOPES QUE NECESITEMOS
}
