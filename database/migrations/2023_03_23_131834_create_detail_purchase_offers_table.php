<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPurchaseOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_purchase_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_offers_id');
            $table->foreign('purchase_offers_id')->references('id')->on('purchase_offers')->onDelete('cascade');
            $table->float('weight');
            $table->String('type');
            $table->String('amount');
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
        Schema::dropIfExists('detail_purchase_offers');
    }
}
