<?php

namespace App\Apiaudiophonemodels;

use Illuminate\Database\Eloquent\Model;
use App\Apiaudiophonemodels\ApiAudiophoneBalance;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiAudiophoneClient extends Model
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

    protected $table = 'apiaudiophoneclients';

    protected $primaryKey = 'apiaudiophoneclients_id';


    /**
     * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [

        'apiaudiophoneclients_name',
        'apiaudiophoneclients_ident',
        'apiaudiophoneclients_phone',
    ];


    //::::: INDICAMOS QUE UN CLIENTE PERTENECE O LO CREA UN USUARIO ::::://

	/**
     * Relacion client vs. user uno a uno
     *
     * @return App\Apiaudiophonemodels\ApiAudiophoneUser 
     */
    public function apiaudiophoneuser(){

        return $this->belogsTo(ApiAudiophoneUser::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    //::::: INDICAMOS QUE UN CLIENTE TIENE VARIOS BALANCES ::::://

    /**
     * Relacion client vs. balance uno a muchos
     *
     * @return App\Apiaudiophonemodels\ApiAudiophoneBalance 
     */
   	public function apiaudiophonebalance()
    {

    	return $this->hasMany(ApiAudiophoneBalance::class, 'id_audiophoneclients', 'apiaudiophoneclients_id');
    }


    //::: SCOPE :::://

    // Consultamos todos los clientes creados por un usuario //

    public function scopeClientUser($query, $id_apiaudiophoneusers){

        if($id_apiaudiophoneusers){

            return $query->where('id_apiaudiophoneusers', $id_apiaudiophoneusers);
        }
    }
}
