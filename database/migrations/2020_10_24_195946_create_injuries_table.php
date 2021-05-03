<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInjuriesTable extends Migration {
    /* Nazev souboru: CreateInjuriesTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci zraneni danych zamestnancu firem
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_injuries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('injury_id');
            $table->string('injury_description')->nullable();
            $table->dateTime('injury_date')->nullable();
            $table->timestamps();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('shift_id')->unsigned();
        });

        Schema::table('table_injuries', function($table) {
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
            $table->foreign('shift_id')->references('shift_id')->on('table_shifts')->onDelete('cascade');
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_injuries');
    }
}
