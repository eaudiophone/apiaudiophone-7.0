<?php

use Illuminate\Database\Seeder;
use App\Apiaudiophonemodels\ApiAudiophoneUser;

class ApiAudiophoneUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(ApiAudiophoneUser::class, 9)->create();

    	ApiAudiophoneUser::create([
    		'apiaudiophoneusers_fullname' => 'Alfonso Martinez',
        	'apiaudiophoneusers_email' => 'a@a.com',
        	'apiaudiophoneusers_password' => app('hash')->make('12345678')
    	]);
    }
}
