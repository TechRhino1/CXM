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
        Schema::create('clients', function (Blueprint $table) {
            $table->ID('ID');
            $table->string('Companies_id');
            $table->string('Name');
            $table->string('Email');
            $table->string('Phone');
            $table->string('leads_id')->default(0);
            $table->string('address');
            $table->string('Comments');
            $table->integer('StaffID');
            $table->tinyInteger('Status')->default(1);
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
        Schema::dropIfExists('clients');
    }
};
