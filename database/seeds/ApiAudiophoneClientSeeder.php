<?php

use App\Apiaudiophonemodels\ApiAudiophoneClient;
use Illuminate\Database\Seeder;

class ApiAudiophoneClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ApiAudiophoneClient::class, 10)->create();
    }
}
