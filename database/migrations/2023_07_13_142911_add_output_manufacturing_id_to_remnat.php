<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutputManufacturingIdToRemnat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('output_remnat_details', function (Blueprint $table) {
            $table->unsignedBigInteger('output_manufacturing_id')->after('output_slaughter_id')->nullable();
            $table->foreign('output_manufacturing_id')->references('id')->on('output_manufacturings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remnat', function (Blueprint $table) {
            //
        });
    }
}
