<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration {
    /* Nazev souboru: CreatePasswordResetsTable.php */
    /* Tato migrace slouzi pro vytvoreni tabulky pro resetování hesel, tato migrace je soucasti Laravel autentizacniho a autorizacniho balicku
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('password_resets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('password_resets');
    }
}
