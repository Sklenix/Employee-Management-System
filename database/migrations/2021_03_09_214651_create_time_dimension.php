<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeDimension extends Migration {
    /* Nazev souboru: CreateTimeDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni dimenze casu v ramci OLAP sekce systemu
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('time_dimension', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('time_id');
            $table->integer('day');
            $table->integer('month');
            $table->integer('quarter')->nullable();
            $table->integer('year');
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('time_dimension');
    }
}
