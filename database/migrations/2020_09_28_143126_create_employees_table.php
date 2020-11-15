<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('employee_name');
            $table->string('employee_surname');
            $table->string('employee_phone');
            $table->string('email')->unique();
            $table->string('employee_note')->nullable();
            $table->string('employee_position');
            $table->string('employee_city');
            $table->string('employee_street')->nullable();
            $table->string('employee_reliability')->nullable();
            $table->string('employee_absence')->nullable();
            $table->string('employee_workindex')->nullable();
            $table->string('employee_drive_url')->nullable();
            $table->string('employee_login')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('employee_company');
            $table->foreign('employee_company')->references('company_id')->on('table_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_employees');
    }
}
