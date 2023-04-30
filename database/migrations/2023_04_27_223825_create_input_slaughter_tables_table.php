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
                $table->unsignedBigInteger('type_id');
                $table->foreign('type_id')->references('id')->on('type_chickens')->onDelete('cascade');
                $table->timestamps();
                $table->timestamp('income_date')->nullable();
                $table->timestamp('output_date')->nullable();
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
