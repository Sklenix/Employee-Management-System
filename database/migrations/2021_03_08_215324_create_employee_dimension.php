<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDimension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_dimension', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('employee_id');
            $table->string('employee_name');
            $table->string('employee_surname');
            $table->string('employee_position');
            $table->string('employee_overall')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_dimension');
    }
}
