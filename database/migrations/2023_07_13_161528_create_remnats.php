<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemnats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remnats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_remant_id');
            $table->foreign('type_remant_id')->references('id')->on('remnants_types')->onDelete('cascade');
            $table->integer('weight');
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
        Schema::dropIfExists('remnats');
    }
}
