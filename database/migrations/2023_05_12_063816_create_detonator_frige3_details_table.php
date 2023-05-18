<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetonatorFrige3DetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detonator_frige3_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('detonator_frige_3_id')->nullable();
            $table->foreign('detonator_frige_3_id')->references('id')->on('detonator_frige3s')->onDelete('cascade');

            $table->float('weight')->nullable();
            $table->integer('amount')->nullable();

            $table->float('cur_weight')->nullable();
            $table->integer('cur_amount')->nullable();

            $table->timestamp('date_of_destruction')->nullable();
            $table->timestamp('expiration_date')->nullable();       
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
        Schema::dropIfExists('detonator_frige3_details');
    }
}
