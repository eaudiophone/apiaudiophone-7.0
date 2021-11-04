<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apiaudiophonemodels\ApiAudiophoneItem;
use Faker\Generator as Faker;

$factory->define(ApiAudiophoneItem::class, function (Faker $faker) {
    return [
        
        'apiaudiophoneitems_name' => $faker->sentence($nbWords = 4, $variableNbWords = true),
        'apiaudiophoneitems_description' => $faker->sentence($nbWords = 3, $variableNbWords = true), 
        'apiaudiophoneitems_status' => $faker->randomElement(['ACTIVO', 'INACTIVO']),
        'apiaudiophoneitems_price' => $faker->randomFloat($nbMaxDecimals = 2)
    ];
});
