<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreateDirectToOutputSlaughtersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_to_output_slaughters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('output_det_s_id');
            $table->foreign('output_det_s_id')->references('id')->on('output_slaughtersupervisors_details')->onDelete('cascade');
            $table->integer('weight');
            $table->enum('direct_to', ['قسم التقطيع', 'قسم التصنيع', 'قسم الذبح'])->nullable();
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
        Schema::dropIfExists('create_direct_to_output_slaughters');
    }
}
