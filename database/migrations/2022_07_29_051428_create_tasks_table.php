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
        Schema::create('tasks', function (Blueprint $table) {
            $table->ID('ID');
            $table->string('Title');
            $table->longText('Description');
            $table->string('ProjectID');
            $table->string('CreaterID');
            $table->string('CreatedDateTime');
            $table->string('EstimatedDate');
            $table->string('EstimatedTime');
            $table->string('Priority');
            $table->string('CurrentStatus');
            $table->string('InitiallyAssignedToID');
            $table->string('CurrentlyAssignedToID');
            $table->string('CompletedDate');
            $table->string('CompletedTime');
            $table->string('ParentID');
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
        Schema::dropIfExists('tasks');
    }
};
