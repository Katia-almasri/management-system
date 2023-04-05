<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToSellingPortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selling_ports', function (Blueprint $table) {
            //
            $table->String('name')->after('id');
            $table->String('type')->after('name');
            $table->String('username')->after('name')->change();
            $table->String('password')->after('username')->change();
            $table->dropColumn('admin');
            $table->dropColumn('date_of_hiring');
            $table->dropColumn('date_of_leave');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selling_ports', function (Blueprint $table) {
            //
        });
    }
}
