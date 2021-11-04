<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiaudiophoneusersCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apiaudiophoneusers', function (Blueprint $table) {
            $table->bigIncrements('apiaudiophoneusers_id');
            $table->string('apiaudiophoneusers_fullname', 60)->required();
            $table->string('apiaudiophoneusers_email', 60)->unique()->required();
            $table->string('apiaudiophoneusers_password', 60)->required();
            $table->string('apiaudiophoneusers_role', 60)->default('USER_ROLE');
            $table->boolean('apiaudiophoneusers_status')->default(true);
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
        Schema::dropIfExists('audiophoneusers');
    }
}
