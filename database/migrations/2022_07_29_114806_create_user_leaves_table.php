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
        Schema::create('user_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('UserID');
            $table->date('DateFrom');
            $table->date('DateTo');
            $table->string('Reason');
            $table->integer('ApprovalStatus');
            $table->integer('ApprovedUserID');
            $table->dateTime('ApprovedDate');
            $table->string('ApprovalComments');
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
        Schema::dropIfExists('user_leaves');
    }
};
