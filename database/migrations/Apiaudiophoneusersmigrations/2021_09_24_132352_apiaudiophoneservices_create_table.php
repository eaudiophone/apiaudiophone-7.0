<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophoneservicesCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('apiaudiophoneservices', function (Blueprint $table) {

            //PrimaryKeyColumn
            $table->bigIncrements('apiaudiophoneservices_id');

            //Columns
            $table->string('apiaudiophoneservices_name', 65)->nullable(true);
            $table->string('apiaudiophoneservices_description', 65)->nullable(true);
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
        Schema::dropIfExists('apiaudiophoneservices');
    }
}
