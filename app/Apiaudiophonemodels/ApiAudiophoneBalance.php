<?php

namespace App\Apiaudiophonemodels;

use Illuminate\Database\Eloquent\Model;
use App\Apiaudiophonemodels\ApiAudiophoneClient;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiAudiophoneBalance extends Model
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

    protected $table = 'apiaudiophonebalances';

    protected $primaryKey = 'apiaudiophonebalances_id';


    /**
     * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [

        'apiaudiophonebalances_date',
        'apiaudiophonebalances_desc',
        'apiaudiophonebalances_horlab',
        'apiaudiophonebalances_tarif',
        'apiaudiophonebalances_debe',
        'apiaudiophonebalances_haber',
        'apiaudiophonebalances_total'
    ];


    //::::: INDICAMOS QUE UN BALANCE PERTENECE O ES CREADO POR UN USUARIO ::::://

	/**
     * Relacion balance vs. user uno a uno
     *
     * @return App\Apiaudiophonemodels\ApiAudiophoneUser 
     */
    public function apiaudiophoneuser(){

        return $this->belongsTo(ApiAudiophoneUser::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    //::::: INDICAMOS QUE UN BALACNE PERTENECE A UN CLIENTE ::::://

    /**
     * Relacion client vs. balance uno a uno
     *
     * @return App\Apiaudiophonemodels\ApiAudiophoneClient 
     */
   	public function apiaudiophoneclient()
    {

    	return $this->belongsTo(ApiAudiophoneClient::class, 'id_audiophoneclients', 'id_audiophoneclients');
    }


    //:::: SCOPES :::://

    public function scopeBalanceClient($query, $id_apiaudiophoneclient){

        if($id_apiaudiophoneclient){

            return $query->where('id_apiaudiophoneclients', $id_apiaudiophoneclient);
        }
    }
}
