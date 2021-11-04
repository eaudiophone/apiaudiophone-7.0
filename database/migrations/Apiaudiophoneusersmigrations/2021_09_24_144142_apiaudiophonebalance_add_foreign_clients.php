<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophonebalanceAddForeignClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophonebalances', function(Blueprint $table){

            $table->unsignedBigInteger('id_apiaudiophoneclients')->nullable(true)->after('apiaudiophonebalances_id');

            $table->foreign('id_apiaudiophoneclients')->references('apiaudiophoneclients_id')->on('apiaudiophoneclients');
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apiaudiophonebalances', function(Blueprint $table){


            $table->dropForeign('apiaudiophonebalances_id_apiaudiophoneclients_foreign');

            $table->dropColumn('id_apiaudiophoneclients');
        });
    }
}
