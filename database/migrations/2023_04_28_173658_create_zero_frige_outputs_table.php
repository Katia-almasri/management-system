<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZeroFrigeOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zero_frige_outputs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('zero_frige_details_id')->nullable();
            $table->foreign('zero_frige_details_id')->references('id')->on('zero_frige_details')->onDelete('cascade');

            $table->timestamp('output_date');
            $table->string('note');
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
        Schema::dropIfExists('zero_frige_outputs');
    }
}
