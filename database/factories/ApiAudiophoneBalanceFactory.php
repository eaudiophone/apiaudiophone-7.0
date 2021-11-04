<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apiaudiophonemodels\ApiAudiophoneBalance;
use Faker\Generator as Faker;

$factory->define(ApiAudiophoneBalance::class, function (Faker $faker) {
    return [
        
        'apiaudiophonebalances_date' => $faker->date($format = 'd-m-Y', $max = 'now'),
        'apiaudiophonebalances_desc' => $faker->text($maxNbChars = 50),
        'apiaudiophonebalances_horlab' => $faker->numberBetween($min = 1, $max = 99),
        'apiaudiophonebalances_tarif' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 10000), 
        'apiaudiophonebalances_debe' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 10000),
        'apiaudiophonebalances_haber' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 10000),
        'apiaudiophonebalances_total' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 10000)
    ];
});
