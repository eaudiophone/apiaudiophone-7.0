<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophoneitemsAddForeignUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophoneitems', function(Blueprint $table){

            $table->unsignedBigInteger('id_apiaudiophoneusers')->nullable(true)->after('apiaudiophoneitems_id');

            $table->foreign('id_apiaudiophoneusers')->references('apiaudiophoneusers_id')->on('apiaudiophoneusers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apiaudiophoneitems', function(Blueprint $table){

            $table->dropForeign('apiaudiophoneitems_id_apiaudiophoneusers_foreign');

            $table->dropColumn('id_apiaudiophoneusers');
        });
    }
}
