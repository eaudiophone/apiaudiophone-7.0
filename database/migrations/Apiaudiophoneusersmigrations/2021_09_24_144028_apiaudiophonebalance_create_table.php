<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiaudiophonebalanceCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiaudiophonebalances', function(Blueprint $table){

            //PrimaryKeyColumn
            $table->bigIncrements('apiaudiophonebalances_id');

            //Columns
            $table->string('apiaudiophonebalances_date', 10)->nullable(true);
            $table->string('apiaudiophonebalances_desc', 60)->nullable(true);
            $table->unsignedInteger('apiaudiophonebalances_horlab')->required();
            $table->unsignedDecimal('apiaudiophonebalances_tarif', 12, 2)->required();
            $table->unsignedDecimal('apiaudiophonebalances_debe', 12, 2)->nullable(true);
            $table->unsignedDecimal('apiaudiophonebalances_haber', 12, 2)->nullable(true);
            $table->decimal('apiaudiophonebalances_total', 12, 2)->required();
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
        Schema::dropIfExists('apiaudiophonebalances');
    }
}
