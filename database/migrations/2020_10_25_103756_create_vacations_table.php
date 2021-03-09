<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_vacations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('vacation_id');
            $table->dateTime('vacation_start')->nullable();
            $table->dateTime('vacation_end')->nullable();
            $table->string('vacation_note')->nullable();
            $table->integer('vacation_state')->default('0');
            $table->timestamps();
            $table->bigInteger('employee_id')->unsigned();
        });

        Schema::table('table_vacations', function($table) {
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_vacations');
    }
}
