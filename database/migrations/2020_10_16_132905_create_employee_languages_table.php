<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_employee_languages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('language_id');
            $table->string('language_name');
            $table->bigInteger('company_id')->unsigned();
        });

        Schema::table('table_employee_languages', function($table) {
            $table->foreign('company_id')->references('company_id')->on('table_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_employee_languages');
    }
}
