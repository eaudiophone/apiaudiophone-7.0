<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophoneclientsCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiaudiophoneclients', function(Blueprint $table) {

            //PrimaryKeyColumn
            $table->bigIncrements('apiaudiophoneclients_id');

            //Columns
            $table->string('apiaudiophoneclients_name', 60)->required();
            $table->unsignedBigInteger('apiaudiophoneclients_ident')->unique()->required();
            $table->string('apiaudiophoneclients_phone', 60)->required();
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
        Schema::dropIfExists('apiaudiophoneclients');
    }
}
