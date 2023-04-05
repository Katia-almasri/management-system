<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminToSellingPortTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selling_ports', function (Blueprint $table) {
            $table->String('username');
            $table->string('password');
            $table->boolean('admin')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('date_of_hiring')->useCurrent();
            $table->timestamp('date_of_leave')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selling_port', function (Blueprint $table) {
            //
        });
    }
}
