<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->String('contract_type');
            $table->boolean('accept')->default(false);
            $table->String('datail');
            $table->unsignedBigInteger('manager_id');
            $table->foreign('manager_id')->references('id')->on('managers')->onDelete('cascade');
            $table->unsignedBigInteger('selling_port_id');
            $table->foreign('selling_port_id')->references('id')->on('selling_ports')->onDelete('cascade');
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
        Schema::dropIfExists('contract');
    }
}
