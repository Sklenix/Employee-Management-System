<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmployeesTableLanguagesTable extends Migration {
    /* Nazev souboru: CreateTableEmployeesTableLanguagesTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci jazyku jednotlivych zamestnancu
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_employee_table_languages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('language_employee_id');
            $table->bigInteger('language_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned();
        });

        Schema::table('table_employee_table_languages', function($table) {
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
            $table->foreign('language_id')->references('language_id')->on('table_company_languages')->onDelete('cascade');
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_employee_table_languages');
    }
}
