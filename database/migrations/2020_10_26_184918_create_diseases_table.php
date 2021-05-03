<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasesTable extends Migration {
    /* Nazev souboru: CreateDiseasesTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci nemocenskych danych zamestnancu firem
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_diseases', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('disease_id');
            $table->string('disease_name')->nullable();
            $table->dateTime('disease_from')->nullable();
            $table->dateTime('disease_to')->nullable();
            $table->integer('disease_state')->default('0');
            $table->string('disease_note')->nullable();
            $table->timestamps();
            $table->bigInteger('employee_id')->unsigned();
        });

        Schema::table('table_diseases', function($table) {
            $table->foreign('employee_id')->references('employee_id')->on('table_employees')->onDelete('cascade');
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_diseases');
    }
}
