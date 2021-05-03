<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration {
    /* Nazev souboru: CreateReportsTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci nahlaseni danych zamestnancu firem
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('report_id');
            $table->string('report_title')->nullable();
            $table->string('report_description')->nullable();
            $table->integer('report_state')->default('0');
            $table->timestamps();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('report_importance_id')->unsigned();
        });

        Schema::table('table_reports', function($table) {
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
            $table->foreign('report_importance_id')->references('importance_report_id')->on('table_reports_importances')->onDelete('restrict');
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_reports');
    }
}
