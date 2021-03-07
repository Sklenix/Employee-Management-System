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
            $table->engine = 'MyISAM';
            $table->id('language_id');
            $table->string('language_name');
            $table->integer('company_id');
            $table->timestamps();
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
