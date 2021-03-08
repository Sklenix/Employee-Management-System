<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportancesShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_importances_shifts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('importance_id');
            $table->integer('importance_value')->nullable();
            $table->string('importance_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_importances_shifts');
    }
}
