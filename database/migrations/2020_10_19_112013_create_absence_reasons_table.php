<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsenceReasonsTable extends Migration {
    /* Nazev souboru: CreateAbsenceReasonsTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci duvodu absence k dochazce, existuji i absence OK, ktera reprezentuje, ze smena byla v poradku
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_absence_reasons', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('reason_id');
            $table->string('reason_description')->nullable();
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_absence_reasons');
    }
}
