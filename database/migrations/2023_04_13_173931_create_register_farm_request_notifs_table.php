<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterFarmRequestNotifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_farm_request_notifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from');
            $table->foreign('from')->references('id')->on('farms')->onDelete('cascade');

            $table->boolean('is_read')->default(0);
            $table->string('owner');
            $table->string('name');

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
        Schema::dropIfExists('register_farm_request_notifs');
    }
}
