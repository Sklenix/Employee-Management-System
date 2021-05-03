<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyLanguagesTable extends Migration {
    /* Nazev souboru: CreateCompanyLanguagesTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci jazyku vytvorenymi firmami
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_company_languages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('language_id');
            $table->string('language_name');
            $table->bigInteger('company_id')->unsigned();
        });

        Schema::table('table_company_languages', function($table) {
            $table->foreign('company_id')->references('company_id')->on('table_companies')->onDelete('cascade');
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('table_company_languages');
    }
}
