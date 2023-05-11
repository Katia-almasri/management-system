<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZeroFrigeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zero_frige_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('zero_frige_id')->nullable();
            $table->foreign('zero_frige_id')->references('id')->on('zero_friges')->onDelete('cascade');

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
        Schema::dropIfExists('zero_frige_details');
    }
}
