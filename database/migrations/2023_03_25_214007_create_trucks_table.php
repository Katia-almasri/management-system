<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mashenism_coordinator_id');
            $table->foreign('mashenism_coordinator_id')->references('id')->on('managers')->onDelete('cascade');
            $table->String('name');
            $table->String('model');
            $table->integer('storage_capacity');
            $table->String('state');//في الرحلة-متاحة-في الصيانة
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
        Schema::dropIfExists('trucks');
    }
}
