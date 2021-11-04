<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apiaudiophonemodels\ApiAudiophoneService;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(ApiAudiophoneService::class, function (Faker $faker) {
    return [
        
		'apiaudiophoneservices_name' => 'alquiler',
		'apiaudiophoneservices_description' => 'alquiler profesional de equipos de sonido'
    ];
});