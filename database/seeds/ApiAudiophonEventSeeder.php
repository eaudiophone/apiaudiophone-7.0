<?php

use Illuminate\Database\Seeder;
use App\Apiaudiophonemodels\ApiAudiophonEvent;

class ApiAudiophonEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(ApiAudiophonEvent::class)->create();
    }
}
