<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDimension extends Migration {
    /* Nazev souboru: CreateCompanyDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni dimenze firem v ramci OLAP sekce systemu
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('company_dimension', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('company_id');
            $table->string('company_name');
            $table->string('company_city')->nullable();
            $table->string('company_street')->nullable();
            $table->string('company_user_name');
            $table->string('company_user_surname');
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('company_dimension');
    }
}
