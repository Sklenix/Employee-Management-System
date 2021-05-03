<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration {
    /* Nazev souboru: CreateAdminTable.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci uzivatelu s roli admina
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('table_admins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('admin_id');
            $table->string('admin_name');
            $table->string('admin_surname');
            $table->string('admin_email');
            $table->string('admin_login');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /* Odstraneni tabulky*/
    public function down(){
        Schema::dropIfExists('table_admins');
    }
}
