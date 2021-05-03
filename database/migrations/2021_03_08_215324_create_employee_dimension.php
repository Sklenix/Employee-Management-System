<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDimension extends Migration {
    /* Nazev souboru: CreateEmployeeDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni dimenze zamestnancu v ramci OLAP sekce systemu
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('employee_dimension', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('employee_id');
            $table->string('employee_name');
            $table->string('employee_surname');
            $table->string('employee_position')->nullable();
            $table->string('employee_overall')->nullable();
        });
    }

    /* Odstraneni dimenze */
    public function down(){
        Schema::dropIfExists('employee_dimension');
    }
}
