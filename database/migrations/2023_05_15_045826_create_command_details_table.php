<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

            $table->unsignedBigInteger('command_id')->nullable();
            $table->foreign('command_id')->references('id')->on('commands')->onDelete('cascade');

            $table->float('input_weight')->nullable();
            $table->float('command_weight')->nullable();
            $table->float('cur_weight')->nullable();

            $table->string('from')->nullable();
            $table->string('to')->nullable();       

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
        Schema::dropIfExists('command_details');
    }
}
