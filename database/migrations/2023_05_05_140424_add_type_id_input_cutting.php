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
            $table->unsignedBigInteger('output_citting_id')->after('output_slaughter_det_Id')->nullable();
            $table->foreign('output_citting_id')->references('id')->on('output_cuttings')->onDelete('cascade');
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
