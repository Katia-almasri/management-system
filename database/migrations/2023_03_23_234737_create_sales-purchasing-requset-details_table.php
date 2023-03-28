<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesPurchasingRequsetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales-purchasing-requset-details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requset_id');
            $table->foreign('requset_id')->references('id')->on('sales_purchasing_requests')->onDelete('cascade');
            $table->integer('amount');
            $table->String('type');
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
        Schema::dropIfExists('sales-purchasing-requset-details');
    }
}
