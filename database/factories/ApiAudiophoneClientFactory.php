<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apiaudiophonemodels\ApiAudiophoneClient;
use Faker\Generator as Faker;

$factory->define(ApiAudiophoneClient::class, function (Faker $faker) {
    return [
        
        'apiaudiophoneclients_name' => $faker->name,
        'apiaudiophoneclients_ident' => $faker->randomNumber($nbDigits = NULL, $strict = false),
        'apiaudiophoneclients_phone' => $faker->phoneNumber,
    ];
});
