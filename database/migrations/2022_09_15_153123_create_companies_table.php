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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('logo')->nullable();
            $table->string('bank_account_name');
            $table->string('bank_account_number')->unique();
            $table->string('bank_account_type');
            $table->string('bank_account_branch');
            $table->string('bank_address');
            $table->string('bank_account_ifsc');
            $table->string('bank_account_swiftcode');
            $table->string('invoice_header');
            $table->string('invoice_company_details');
            $table->string('invoice_sub_header_left');
            $table->string('invoice_sub_header_right');
            $table->string('invoice_footer');
            $table->text('template_content');
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
        Schema::dropIfExists('companies');
    }
};
