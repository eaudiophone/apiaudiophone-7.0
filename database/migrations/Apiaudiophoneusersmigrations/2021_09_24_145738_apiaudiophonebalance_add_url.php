<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophonebalanceAddUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophoneclients', function(Blueprint $table){

            $table->string('apiaudiophoneclients_url', 250)->after('apiaudiophoneclients_phone')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apiaudiophoneclients', function(Blueprint $table){

            $table->dropColumn('apiaudiophoneclients_url');
        });
    }
}
