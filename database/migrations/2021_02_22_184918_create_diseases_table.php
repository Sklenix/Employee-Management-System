<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_diseases', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id('disease_id');
            $table->string('disease_name')->nullable();
            $table->dateTime('disease_from')->nullable();
            $table->dateTime('disease_to')->nullable();
            $table->integer('disease_state')->default('0');
            $table->string('disease_note')->nullable();
            $table->timestamps();
            $table->integer('employee_id');
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
        Schema::dropIfExists('table_diseases');
    }
}
