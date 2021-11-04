<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophoneventsAddForeignServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophonevents', function(Blueprint $table){

            $table->unsignedBigInteger('id_apiaudiophoneservices')->nullable(true)->after('id_apiaudiophoneusers');

            $table->foreign('id_apiaudiophoneservices')->references('apiaudiophoneservices_id')->on('apiaudiophoneservices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apiaudiophonevents', function(Blueprint $table){

            $table->dropForeign('apiaudiophonevents_id_apiaudiophoneservices_foreign');

            $table->dropColumn('id_apiaudiophoneservices');
        });
    }
}
