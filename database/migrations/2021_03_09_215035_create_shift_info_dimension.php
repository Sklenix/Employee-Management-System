<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftInfoDimension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_info_dimension', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('shift_info_id');
            $table->dateTime('shift_start')->nullable();
            $table->dateTime('shift_end')->nullable();
            $table->integer('attendance_came')->nullable();
            $table->integer('absence_reason_value')->nullable();
            $table->dateTime('attendance_check_in')->nullable();
            $table->dateTime('attendance_check_out')->nullable();
            $table->dateTime('attendance_check_in_company')->nullable();
            $table->dateTime('attendance_check_out_company')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_info_dimension');
    }
}
