<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmployeesTableLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_employee_table_languages', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id('language_employee_id');
            $table->integer('language_id');
            $table->integer('employee_id');
            $table->timestamps();
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
            $table->foreign('language_id')->references('language_id')->on('table_employee_languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_employee_table_languages');
    }
}
