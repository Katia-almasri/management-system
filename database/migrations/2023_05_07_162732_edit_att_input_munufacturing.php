<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditAttInputMunufacturing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('input_manufacturings', function (Blueprint $table) {
            $table->unsignedBigInteger('direct_to_output_slaughters_id')->nullable()->after('output_manufacturing_id');
            $table->foreign('direct_to_output_slaughters_id')->references('id')->on('direct_to_output_slaughters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
