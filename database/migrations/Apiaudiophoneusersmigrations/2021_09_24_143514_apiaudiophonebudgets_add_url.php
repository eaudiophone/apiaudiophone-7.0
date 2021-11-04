<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophonebudgetsAddUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apiaudiophonebudgets', function(Blueprint $table){

            $table->string('apiaudiophonebudgets_url', 200)->after('apiaudiophonebudgets_total_price')->nullable();
            $table->enum('apiaudiophonebudgets_status', ['PAGADO', 'PENDIENTE', 'NO_APLICA'])->after('apiaudiophonebudgets_url')->default('PENDIENTE');
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

            $table->dropColumn('apiaudiophonebudgets_url');
            $table->dropColumn('apiaudiophonebudgets_status');
        });
    }
}
