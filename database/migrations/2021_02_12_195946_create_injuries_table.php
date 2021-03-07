<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInjuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_injuries', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id('injury_id');
            $table->string('injury_description')->nullable();
            $table->dateTime('injury_date')->nullable();
            $table->timestamps();
            $table->integer('employee_id');
            $table->integer('shift_id');
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
        Schema::dropIfExists('table_injuries');
    }
}
