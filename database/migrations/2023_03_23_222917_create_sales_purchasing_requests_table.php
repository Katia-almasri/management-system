<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesPurchasingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_purchasing_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ceo_id');
            $table->foreign('ceo_id')->references('id')->on('managers')->onDelete('cascade');

            $table->unsignedBigInteger('purchasing_manager_id');
            $table->foreign('purchasing_manager_id')->references('id')->on('managers')->onDelete('cascade');

            $table->unsignedBigInteger('farm_id')->nullable();
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');

            $table->unsignedBigInteger('selling_port_id')->nullable();
            $table->foreign('selling_port_id')->references('id')->on('selling_ports')->onDelete('cascade');

            $table->boolean('is_seen')->default(0);
            $table->boolean('accept')->default(0);
            $table->boolean('command')->default(0);
            $table->integer('total_amount');
            $table->boolean('request_type');

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
        Schema::dropIfExists('sales_purchasing_requests');
    }
}
