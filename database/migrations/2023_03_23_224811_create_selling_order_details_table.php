<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selling_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('selling_order_id');
            $table->foreign('selling_order_id')->references('id')->on('selling_orders')->onDelete('cascade');
            $table->String('details');
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
        Schema::dropIfExists('selling_order_details');
    }
}
