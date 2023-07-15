<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemnantWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remnant_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remnant_type')->nullable();
            $table->foreign('remnant_type')->references('id')->on('remnant_types')->onDelete('cascade');

            $table->float('tot_weight')->nullable();
            
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
        Schema::dropIfExists('remnant_warehouses');
    }
}
