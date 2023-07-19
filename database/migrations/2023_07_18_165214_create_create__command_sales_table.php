<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreateCommandSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_request_id');
            $table->foreign('sales_request_id')->references('id')->on('sales_purchasing_requests')->onDelete('cascade');
            $table->boolean('done');
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
        Schema::dropIfExists('create__command_sales');
    }
}
