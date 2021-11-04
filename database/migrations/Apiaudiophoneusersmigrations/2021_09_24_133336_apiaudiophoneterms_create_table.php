<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophonetermsCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiaudiophoneterms', function (Blueprint $table) {

            //PrimaryKeyColumn
            $table->bigIncrements('apiaudiophoneterms_id');

            //Columns
            $table->integer('apiaudiophoneterms_quantityeventsweekly')->required();
            $table->integer('apiaudiophoneterms_quantityeventsmonthly')->required();
            $table->string('apiaudiophoneterms_rankevents', 65)->required();
            $table->string('apiaudiophoneterms_daysevents', 65)->nullable(true);
            
            $table->time('apiaudiophoneterms_begintime', 4)->required();
            $table->time('apiaudiophoneterms_finaltime', 4)->required();
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
        Schema::dropIfExists('apiaudiophoneterms');
    }
}
