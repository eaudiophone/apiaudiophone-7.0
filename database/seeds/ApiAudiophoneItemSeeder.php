<?php

use Illuminate\Database\Seeder;
use App\Apiaudiophonemodels\ApiAudiophoneItem;

class ApiAudiophoneItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ApiAudiophoneItem::class, 10)->create();
    }
}
