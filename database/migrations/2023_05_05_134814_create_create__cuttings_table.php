<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreateCuttingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_cuttings', function (Blueprint $table) {
            $table->id();
            $table->integer('weight');
            $table->timestamp('income_date')->nullable();
            $table->timestamp('output_date')->nullable();
            $table->boolean('cutting_done')->nullable()->comment('صفر تتم العملية و واحد تم انهاء التقطيع');
            $table->unsignedBigInteger('output_slaughter_det_Id')->nullable();
            $table->foreign('output_slaughter_det_Id')->references('id')->on('output_slaughtersupervisors_details')->onDelete('cascade');
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
        Schema::dropIfExists('create__cuttings');
    }
}
