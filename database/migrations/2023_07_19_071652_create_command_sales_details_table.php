<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_sales_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('command_id');
            $table->foreign('command_id')->references('id')->on('command_sales')->onDelete('cascade');

            $table->unsignedBigInteger('req_detail_id');
            $table->foreign('req_detail_id')->references('id')->on('sales-purchasing-requset-details')->onDelete('cascade');

            $table->float('cur_weight')->default(0);
            $table->string('from');
            $table->string('to');
            $table->boolean('is_filled')->default(0);
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
        Schema::dropIfExists('command_sales_details');
    }
}
