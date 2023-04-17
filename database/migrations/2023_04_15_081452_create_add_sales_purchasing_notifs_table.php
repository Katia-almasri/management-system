<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddSalesPurchasingNotifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_sales_purchasing_notifs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('selling_port_id')->nullable();
            $table->foreign('selling_port_id')->references('id')->on('selling_ports')->onDelete('cascade');

            $table->unsignedBigInteger('farm_id')->nullable();
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');

            $table->boolean('is_read')->default(0);
            $table->string('total_amount');

            $table->string('type');

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
        Schema::dropIfExists('add_sales_purchasing_notifs');
    }
}
