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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('Description');
            $table->decimal('Billing');
            $table->string('BillingType');
            $table->integer('TotalHours');
            $table->string('Status');
            $table->integer('HourlyINR');
            $table->string('Currency');
            $table->string('StartDate');
            $table->string('EndDate');
            $table->integer('TotalClientHours');
            $table->integer('UserID');
            $table->string('Comments');
            $table->string('InternalComments');
            $table->integer('ClientID');
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
        Schema::dropIfExists('projects');
    }
};
