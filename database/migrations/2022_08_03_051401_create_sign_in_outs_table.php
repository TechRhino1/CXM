<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signinout', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('EVENTDATE');
            $table->time('SIGNIN_TIME')->default('00:00');
            $table->date('CREATEDSIGNIN_DATE');
            $table->string('CREATEDSIGNIN_TIME')->default('00:00');
            $table->string('SIGNOUT_TIME')->default('00:00');
            $table->date('CREATEDSIGNOUT_DATE')->nullable();
            $table->string('CREATEDSIGNOUT_TIME')->default('00:00')->nullable();
            $table->integer('TotalMins')->default(0);
            $table->integer('TotalTaskMins')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sign_in_outs');
    }
};
