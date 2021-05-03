<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsImportancesTable extends Migration {
    /* Nazev souboru: CreateReportsImportancesTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci dulezitosti nahlaseni
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_reports_importances', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('importance_report_id');
            $table->string('importance_report_value')->nullable();
            $table->string('importance_report_description')->nullable();
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_reports_importances');
    }
}
