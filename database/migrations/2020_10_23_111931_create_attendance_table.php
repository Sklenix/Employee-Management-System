<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_attendances', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('attendance_id');
            $table->integer('attendance_came')->default('0');
            $table->string('attendance_note')->nullable();
            $table->dateTime('attendance_check_in')->nullable();
            $table->dateTime('attendance_check_out')->nullable();
            $table->dateTime('attendance_check_in_company')->nullable();
            $table->dateTime('attendance_check_out_company')->nullable();
            $table->bigInteger('absence_reason_id')->unsigned()->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->bigInteger('shift_id')->unsigned()->nullable();
        });

        Schema::table('table_attendances', function($table) {
            $table->foreign('absence_reason_id')->references('reason_id')->on('table_absence_reasons')->onDelete('restrict');
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
            $table->foreign('shift_id')->references('shift_id')->on('table_shifts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_attendances');
    }
}
