<?php

use Illuminate\Database\Seeder;
use App\Apiaudiophonemodels\ApiAudiophoneTerm;

class ApiAudiophoneTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	//creamos tres registros falsos usando el factory
    	factory(ApiAudiophoneTerm::class, 3)->create();
        
    }
}
