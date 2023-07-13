<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemnantsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remnants_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('by_section', ['قسم التقطيع', 'قسم التصنيع', 'قسم الذبح'])->nullable();
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
        Schema::dropIfExists('remnants_types');
    }
}
