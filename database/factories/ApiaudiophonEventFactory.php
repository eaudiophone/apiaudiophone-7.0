<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apiaudiophonemodels\ApiAudiophonEvent;
use Faker\Generator as Faker;

$factory->define(ApiAudiophonEvent::class, function (Faker $faker) {
    return [

        'apiaudiophonevents_title' => $faker->sentence($nbWords = 10, $variableNbWords = true),
    	'apiaudiophonevents_address' => $faker->sentence($nbWords = 10, $variableNbWords = true),
    	'apiaudiophonevents_description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
    	'apiaudiophonevents_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    	'apiaudiophonevents_begintime' => $faker->time($format = 'H:i'),
    	'apiaudiophonevents_finaltime' => $faker->time($format = 'H:i'),
    	'apiaudiophonevents_totalhours' => $faker->time($format = 'H:i')
    ];
});
