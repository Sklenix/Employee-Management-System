<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportancesShiftsTable extends Migration {
    /* Nazev souboru: CreateImportancesShiftsTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci dulezitosti smen
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_importances_shifts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('importance_id');
            $table->integer('importance_value')->nullable();
            $table->string('importance_description')->nullable();
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_importances_shifts');
    }
}
