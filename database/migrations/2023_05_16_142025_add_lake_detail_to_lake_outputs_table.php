<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLakeDetailToLakeOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lake_outputs', function (Blueprint $table) {
            $table->unsignedBigInteger('lake_id')->nullable();
            $table->foreign('lake_id')->references('id')->on('lakes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lake_outputs', function (Blueprint $table) {
            //
        });
    }
}
