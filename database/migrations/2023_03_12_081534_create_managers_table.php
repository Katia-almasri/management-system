<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->enum('managing_level', ['Purchasing-and-Sales-manager', 'ceo', 'Mechanism-Coordinator',
                'Production_Manager', 'libra-commander', 'Accounting-Manager', 'slaughter_supervisor',
                 'cutting_supervisor' ,'Manufacturing_Supervisor', 'warehouse_supervisor'
        ]);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
            $table->timestamp('date_of_hiring')->useCurrent();
            $table->timestamp('date_of_leave')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}
