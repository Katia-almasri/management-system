<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutputCuttingId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('output_remnat_details', function (Blueprint $table) {
            $table->unsignedBigInteger('output_cutting_id')->after('output_slaughter_id')->nullable();
            $table->foreign('output_cutting_id')->references('id')->on('output_cuttings')->onDelete('cascade');
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
