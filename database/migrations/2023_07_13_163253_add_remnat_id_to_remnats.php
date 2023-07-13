<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemnatIdToRemnats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remnat_details', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('remant_id');
            $table->foreign('remant_id')->references('id')->on('remnats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remnats', function (Blueprint $table) {
            //
        });
    }
}
