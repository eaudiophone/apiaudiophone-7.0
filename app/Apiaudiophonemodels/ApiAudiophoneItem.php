<?php

namespace App\Apiaudiophonemodels;

use Illuminate\Database\Eloquent\Model;
use App\Apiaudiophonemodels\ApiAudiophoneUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiAudiophoneItem extends Model
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

    protected $table = 'apiaudiophoneitems';

    protected $primaryKey = 'apiaudiophoneitems_id';


    /**
     * The attributes that are mass assignable.
	 *
	 * @var array
	 */
    protected $fillable = [

        'apiaudiophoneitems_name',
        'apiaudiophoneitems_description',
        'apiaudiophoneitems_price',
    ];

    //Relaciones ApiAudiophoneItem --pendiente.

    /**
    * Relacion item vs. user uno a muchos
    *
    * @return App\Apiaudiophonemodels\ApiAudiophoneUser
    */
    public function apiaudiophoneuser()
    {

        return $this->belongsTo(ApiAudiophoneUser::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }

    //:::: SCOPES :::://    
}
