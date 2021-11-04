<?php

use App\Apiaudiophonemodels\ApiAudiophoneBalance;
use Illuminate\Database\Seeder;

class ApiAudiophoneBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ApiAudiophoneBalance::class, 10)->create();
    }
}
