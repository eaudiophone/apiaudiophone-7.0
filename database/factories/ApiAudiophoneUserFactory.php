<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apiaudiophonemodels\ApiAudiophoneUser;
use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(ApiAudiophoneUser::class, function (Faker $faker) {
    return [

        'apiaudiophoneusers_fullname' => $faker->name,
    	'apiaudiophoneusers_email' => $faker->unique()->freeEmail,
    	'apiaudiophoneusers_password' => app('hash')->make(Str::random(5)),
    	'apiaudiophoneusers_role' => $faker->randomElement(['USER_ROLE', 'ADMIN_ROLE'])
    ];
});