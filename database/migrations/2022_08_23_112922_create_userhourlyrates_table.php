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
        Schema::create('userhourlyrates', function (Blueprint $table) {
            $table->id();
            $table->string('UserID');
            $table->string('HourlyRate');
            $table->string('MonthID');
            $table->string('YearID');
            $table->string('Salary');
            $table->string('OverHead');
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
        Schema::dropIfExists('userhourlyrates');
    }
};
