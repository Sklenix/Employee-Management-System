<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedJobsTable extends Migration {
    /* Nazev souboru: CreateFailedJobsTable.php */
    /* Tato migrace slouzi pro vytvoreni tabulky pro evidenci chyb, tato migrace je soucasti frameworku Laravel
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /* Odstraneni tabulky*/
    public function down(){
        Schema::dropIfExists('failed_jobs');
    }
}
