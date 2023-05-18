<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFillCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fill_commands', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('command_id')->nullable();
            $table->foreign('command_id')->references('id')->on('commands')->onDelete('cascade');

            $table->float('input_weight')->nullable();
            $table->morphs('fillable');

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
        Schema::dropIfExists('fill_commands');
    }
}
