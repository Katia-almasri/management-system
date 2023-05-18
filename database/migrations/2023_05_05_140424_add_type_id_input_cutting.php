<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeIdInputCutting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('input_cuttings', function (Blueprint $table) {
            $table->unsignedBigInteger('output_citting_id')->after('cutting_done')->nullable();
            $table->foreign('output_citting_id')->references('id')->on('output_cuttings')->onDelete('cascade');
            $table->unsignedBigInteger('type_id')->after('output_citting_id');
            $table->foreign('type_id')->references('id')->on('output_production_types')->onDelete('cascade');
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
