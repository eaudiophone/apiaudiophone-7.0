<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AudiophonetermsAddForeignService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophoneterms', function(Blueprint $table){

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
        Schema::table('apiaudiophoneterms', function(Blueprint $table){

            $table->dropForeign('apiaudiophoneterms_id_apiaudiophoneservices_foreign');

            $table->dropColumn('id_apiaudiophoneservices');
        });
    }
}
