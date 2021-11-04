<?php

use App\Apiaudiophonemodels\ApiAudiophoneBudget;
use Illuminate\Database\Seeder;

class ApiAudiophoneBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ApiAudiophoneBudget::class, 10)->create();
    }
}
