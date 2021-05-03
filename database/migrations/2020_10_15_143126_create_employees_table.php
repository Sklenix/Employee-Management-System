<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration{
    /* Nazev souboru: CreateEmployeesTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci uzivatelu s roli zamestnance.
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_employees', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('employee_id');
            $table->string('employee_name')->nullable();
            $table->string('employee_surname')->nullable();
            $table->string('employee_phone')->nullable();
            $table->string('email')->unique();
            $table->string('employee_note')->nullable();
            $table->string('employee_position')->nullable();
            $table->string('employee_city')->nullable();
            $table->date('employee_birthday')->nullable();
            $table->string('employee_street')->nullable();
            $table->string('employee_reliability')->nullable();
            $table->string('employee_absence')->nullable();
            $table->string('employee_workindex')->nullable();
            $table->string('employee_overall')->nullable();
            $table->string('employee_url')->nullable();
            $table->string('employee_picture')->nullable();
            $table->string('employee_login')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->bigInteger('employee_company')->unsigned();
        });

        Schema::table('table_employees', function($table) {
            $table->foreign('employee_company')->references('company_id')->on('table_companies')->onDelete('cascade');
        });

    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_employees');
    }
}
