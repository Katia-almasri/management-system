<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLakeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lake_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lake_id')->nullable();
            $table->foreign('lake_id')->references('id')->on('lakes')->onDelete('cascade');

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
        Schema::dropIfExists('lake_details');
    }
}
