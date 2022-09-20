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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('month');
            $table->string('date_created');
            $table->string('user_created');
            $table->string('invoice_date');
            $table->string('client_id');
            $table->string('currency');
            $table->string('amount');
            $table->string('status');
            $table->string('amount_received');
            $table->string('conversion_rate');
            $table->string('date_received');
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
        Schema::dropIfExists('invoices');
    }
};
