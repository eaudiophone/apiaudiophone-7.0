<?php

namespace App\Apiaudiophonemodels;

use Illuminate\Auth\Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Support\Facades\Hash;
use App\Apiaudiophonemodels\ApiAudiophoneTerm;
use App\Apiaudiophonemodels\ApiAudiophoneService;
use App\Apiaudiophonemodels\ApiAudiophonEvent;
use App\Apiaudiophonemodels\ApiAudiophoneItem;
use App\Apiaudiophonemodels\ApiAudiophoneBudget;
use App\Apiaudiophonemodels\ApiAudiophoneClient;
use App\Apiaudiophonemodels\ApiAudiophoneBalance;

class ApiAudiophoneUser extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, SoftDeletes;

    /**
    * The attributes should be mutatedto dates
    *
    * @var array
    */
    protected $dates = [
        'deleted_at'
    ];

    protected $table = 'apiaudiophoneusers';

    protected $primaryKey = 'apiaudiophoneusers_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'apiaudiophoneusers_fullname',
        'apiaudiophoneusers_email',
        'apiaudiophoneusers_password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'apiaudiophoneusers_password'
    ];

    
    /**
    * Relacion user vs. term uno a muchos
    *
    * @return App\Apiaudiophonemodels\ApiAudiophoneTerm
    */
    public function apiaudiophoneterm()
    {

        return $this->hasMany(ApiAudiophoneTerm::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }

    /**
     * Relacion user vs. event uno a muchos
     *
     * @return App\Apiaudiophonemodels\ApiAudiophonEvent 
     */
    public function apiaudiophonevent(){

        return $this->hasMany(ApiAudiophonEvent::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    /**
     * Relacion user vs. item uno a muchos
     *
     * @return App\Apiaudiophonemodels\ApiAudiophoneItem 
     */
    public function apiaudiophoneitem(){

        return $this->hasMany(ApiAudiophoneItem::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    /**
     * Relacion user vs. budget uno a muchos
     *
     * @return App\Apiaudiophonemodels\ApiAudiophoneBudget 
     */
    public function apiaudiophonebudget(){

        return $this->hasMany(ApiAudiophoneBudget::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    /**
    * Relacion user vs. client uno a muchos
    *
    * @return App\Apiaudiophonemodels\ApiAudiophoneClient
    */
    public function apiaudiophoneclient()
    {

        return $this->hasMany(ApiAudiophoneClient::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    /**
    * Relacion user vs. balance uno a muchos
    *
    * @return App\Apiaudiophonemodels\ApiAudiophoneBalance
    */
    public function apiaudiophonebalance()
    {

        return $this->hasMany(ApiAudiophoneBalance::class, 'id_apiaudiophoneusers', 'apiaudiophoneusers_id');
    }


    /**
     * Relacion oauth_acces_token vs. apiaudiophonesusers uno a uno, AHORITA ESTA RELACION NO SE USA porq era para meterlo en un middleware
     * de validación de token, en su lugar se personalizo la clase Handling de las excepciones.
     *
     * @return Laravel\Passport\Token
     */
    public function oauth_acces_token()
    {

        return $this->hasOne('Laravel\Passport\Token', 'user_id', 'apiaudiophoneusers_id');
    }

    /**
     * Funcion para validar  que el username corresponda al campo apioaudiophoneusers_email
     * para el request del token.
     *
     * @param  string  $username
     * @return App\Apiaudiophonemodels
     */
    public function findForPassport($username)
    {

        return $this->where('apiaudiophoneusers_email', $username)->first();
    }

     /**
     * Funcion para validar que el hash del password que le pasamos por el request sea el mismo
     * que está almacenado en la base de datos.
     *
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {

        return Hash::check($password, $this->apiaudiophoneusers_password);
    }

    //:::: SCOPES :::://
    
    public function scopeItemUser($query, $id_apiaudiophoneusers){

        if($id_apiaudiophoneusers){

            return $query->where('apiaudiophoneusers_id', $id_apiaudiophoneusers);
        }
    }

    public function scopeUserClient($query, $id_apiaudiophoneusers){

        if($id_apiaudiophoneusers){

            return $query->where('apiaudiophoneusers_id', $id_apiaudiophoneusers);
        }
    }


    public function scopeUserBalance($query, $id_apiaudiophoneusers){

        if($id_apiaudiophoneusers){

            return $query->where('apiaudiophoneusers_id', $id_apiaudiophoneusers);
        }
    }

    
    public function scopeBudgetUser($query, $id_apiaudiophoneusers){

        if($id_apiaudiophoneusers){

            return $query->where('apiaudiophoneusers_id', $id_apiaudiophoneusers);
        }
    }
}