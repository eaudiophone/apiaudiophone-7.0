<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophonebudgetsCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiaudiophonebudgets', function (Blueprint $table) {

            //PrimarykeyColumn
            $table->bigIncrements('apiaudiophonebudgets_id');


            //Columns
            $table->string('apiaudiophonebudgets_client_name', 60)->required();
            $table->string('apiaudiophonebudgets_client_email', 60)->required();
            $table->string('apiaudiophonebudgets_client_phone', 60)->required();
            $table->string('apiaudiophonebudgets_client_social', 60)->required();
            $table->float('apiaudiophonebudgets_total_price', 10, 2)->required();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apiaudiophonebudgets');
    }
}
