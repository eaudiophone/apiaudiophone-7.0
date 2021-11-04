<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophoneclientsAddForeignUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophoneclients', function(Blueprint $table){


            $table->unsignedBigInteger('id_apiaudiophoneusers')->nullable(true)->after('apiaudiophoneclients_id');

            $table->foreign('id_apiaudiophoneusers')->references('apiaudiophoneusers_id')->on('apiaudiophoneusers');
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
        Schema::table('apiaudiophoneclients', function(Blueprint $table){


            $table->dropForeign('apiaudiophoneclients_id_apiaudiophoneusers_foreign');

            $table->dropColumn('id_apiaudiophoneusers');
        });
    }
}
