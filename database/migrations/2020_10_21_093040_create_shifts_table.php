<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_shifts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('shift_id');
            $table->dateTime('shift_start')->nullable();
            $table->dateTime('shift_end')->nullable();
            $table->string('shift_note')->nullable();
            $table->string('shift_place')->nullable();
            $table->bigInteger('shift_importance_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('table_shifts', function($table) {
            $table->foreign('shift_importance_id')->references('importance_id')->on('table_importances_shifts')->onDelete('restrict');
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
        Schema::dropIfExists('table_shifts');
    }
}
