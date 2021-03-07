<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_reports', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->id('report_id');
            $table->string('report_title')->nullable();
            $table->string('report_description')->nullable();
            $table->string('report_note')->nullable();
            $table->integer('report_state')->default('0');
            $table->timestamps();
            $table->integer('employee_id');
            $table->integer('report_importance_id');
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
            $table->foreign('report_importance_id')->references('report_importance_id')->on('table_reports_importances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_reports');
    }
}
