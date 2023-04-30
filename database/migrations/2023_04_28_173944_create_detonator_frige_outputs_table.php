<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetonatorFrigeOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detonator_frige_outputs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('detonator_frige_details_id')->nullable();
            $table->foreign('detonator_frige_details_id')->references('id')->on('detonator_frige_details')->onDelete('cascade');

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
        Schema::dropIfExists('detonator_frige_outputs');
    }
}
