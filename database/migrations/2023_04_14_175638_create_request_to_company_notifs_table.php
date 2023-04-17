<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestToCompanyNotifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_to_company_notifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from');
            $table->foreign('from')->references('id')->on('selling_ports')->onDelete('cascade');

            $table->boolean('is_read')->default(0);
            $table->string('total_amount');

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
        Schema::dropIfExists('request_to_company_notifs');
    }
}
