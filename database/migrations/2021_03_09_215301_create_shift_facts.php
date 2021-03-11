<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftFacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_facts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->double('shift_total_hours');
            $table->double('absence_total_hours');
            $table->double('employee_overall');
            $table->integer('employee_late_flag');
            $table->integer('employee_injury_flag');
            $table->integer('absence_reason_value')->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('time_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('shift_info_id')->unsigned();
        });

        Schema::table('shift_facts', function($table) {
            $table->foreign('company_id')->references('company_id')->on('company_dimension')->onDelete('cascade');
            $table->foreign('time_id')->references('time_id')->on('time_dimension')->onDelete('cascade');
            $table->foreign('employee_id')->references('employee_id')->on('employee_dimension')->onDelete('cascade');
            $table->foreign('shift_info_id')->references('shift_info_id')->on('shift_info_dimension')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_facts');
    }
}
