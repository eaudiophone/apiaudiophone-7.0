<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apiaudiophonemodels\ApiAudiophoneBudget;
use Faker\Generator as Faker;

$factory->define(ApiAudiophoneBudget::class, function (Faker $faker) {
    return [
        
        'id_apiaudiophoneusers' => 10,
        'id_apiaudiophoneservices' => $faker->randomElement([1, 2]), 
        'apiaudiophonebudgets_nameservice' => $faker->randomElement(['alquiler', 'grabacion']),
        'apiaudiophonebudgets_client_name' => $faker->name,
        'apiaudiophonebudgets_client_email' => $faker->unique()->freeEmail,
        'apiaudiophonebudgets_client_phone' => $faker->tollFreePhoneNumber,
        'apiaudiophonebudgets_client_social' => $faker->randomElement(['IG: @foncho', 'FB: ponchoFB', 'TW: #TW_poncho']),
        'apiaudiophonebudgets_total_price' => $faker->randomFloat($nbMaxDecimals = 2, $max = 10), 
        'apiaudiophonebudgets_url' => $faker->url,
        'apiaudiophonebudgets_status' => $faker->randomElement(['NO_APLICA', 'PENDIENTE', 'PAGADO'])
    ];
});
