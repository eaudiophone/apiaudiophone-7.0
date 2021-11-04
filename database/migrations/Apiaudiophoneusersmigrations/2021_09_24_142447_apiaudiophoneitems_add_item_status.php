<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophoneitemsAddItemStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophoneitems', function(Blueprint $table){

            $table->enum('apiaudiophoneitems_status', ['ACTIVO', 'INACTIVO'])->after('apiaudiophoneitems_price')->default('ACTIVO');
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

            $table->dropColumn('apiaudiophoneitems_status');
        });
    }
}
