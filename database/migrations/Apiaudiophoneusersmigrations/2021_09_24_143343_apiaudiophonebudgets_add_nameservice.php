<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophonebudgetsAddNameservice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophonebudgets', function(Blueprint $table){

            $table->string('apiaudiophonebudgets_nameservice', 60)->required()->after('apiaudiophonebudgets_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apiaudiophonebudgets', function(Blueprint $table){

            $table->dropColumn('apiaudiophonebudgets_nameservice');
        });
    }
}
