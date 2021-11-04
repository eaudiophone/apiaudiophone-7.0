<?php

namespace App\Apiaudiophonemodels;

use Illuminate\Database\Eloquent\Model;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use App\Apiaudiophonemodels\ApiAudiophoneService;
use App\Apiaudiophonemodels\ApiAudiophonEvent;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiAudiophoneTerm extends Model
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


    protected $table = 'apiaudiophoneterms';

    protected $primaryKey = 'apiaudiophoneterms_id';


	/**
     * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [

        'apiaudiophoneterms_quantityeventsweekly',
        'apiaudiophoneterms_quantityeventsmonthly',
        'apiaudiophoneterms_rankevents',
        'apiaudiophoneterms_daysevents',
        'apiaudiophoneterms_begintime',
        'apiaudiophoneterms_finaltime'
    ];


   /*public function apiaudiophoneuser()
    {

        //::::: INDICAMOS QUE UNA CONDICION O TERMINO DE SERVICIO ES CONFIGURADO POR UN UDUARIO ::::://

    	return $this->belongsTo(ApiAudiophoneUser::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }*/


    //::::: INDICAMOS QUE UN TERMINO PERTENECE A UN SERVICIO ::::://

    public function apiaudiophoneservice()
    {

        return $this->belongsTo(ApiAudiophoneService::class, 'id_apiaudiophoneservices', 'apiaudiophoneservices_id');
    }

    //::::: INDICAMOS QUE UN TERMINO RIGE A VARIOS EVENTOS ::::://

    public function apiaudiophonevent()
    {

        return $this->hasMany(ApiAudiophonEvent::class, 'id_apiaudiophoneterms', 'apiaudiophoneterms_id');
    }
}
