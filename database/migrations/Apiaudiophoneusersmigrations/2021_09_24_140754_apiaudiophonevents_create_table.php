<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophoneventsCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('apiaudiophonevents', function (Blueprint $table) {

            //PrimarykeyColumn
            $table->bigIncrements('apiaudiophonevents_id');


            //Columns
            $table->string('apiaudiophonevents_title', 120)->required();
            $table->date('apiaudiophonevents_date')->required();
            $table->time('apiaudiophonevents_begintime', 4)->required();
            $table->time('apiaudiophonevents_finaltime', 4)->required();
            $table->time('apiaudiophonevents_totalhours', 4)->required();
            $table->string('apiaudiophonevents_address', 120)->nullable(true);
            $table->string('apiaudiophonevents_description', 120)->required();
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
        Schema::dropIfExists('apiaudiophonevents');
    }
}
