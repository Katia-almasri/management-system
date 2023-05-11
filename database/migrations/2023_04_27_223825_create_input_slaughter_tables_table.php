<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputSlaughterTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_slaughters', function (Blueprint $table) {
                $table->id();
                $table->integer('weight');
                $table->enum('status', ['يتم الذبح', 'تم انهاء الذبح'])->nullable();
                $table->unsignedBigInteger('weight_after_id');
                $table->foreign('weight_after_id')->references('id')->on('weight_after_arrival_detections')->onDelete('cascade');
                $table->timestamp('income_date')->nullable();
                $table->timestamp('output_date')->nullable();
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
        Schema::dropIfExists('input_slaughter_tables');
    }
}
