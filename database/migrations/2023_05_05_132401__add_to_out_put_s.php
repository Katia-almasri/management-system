<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToOutPutS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('output_slaughtersupervisors_details', function (Blueprint $table) {
            $table->enum('direct_to', ['قسم التقطيع', 'قسم التصنيع', 'المستودع'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('out_put_s', function (Blueprint $table) {
            //
        });
    }
}
