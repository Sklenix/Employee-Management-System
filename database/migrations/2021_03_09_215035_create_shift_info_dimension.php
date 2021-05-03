<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftInfoDimension extends Migration {
    /* Nazev souboru: CreateShiftInfoDimension.php */
    /* Autor: Pavel Sklenář (xsklen12) */
    /* Tato migrace slouzi pro vytvoreni dimenze informaci o smenach v ramci OLAP sekce systemu
       Migrace detailneji: https://laravel.com/docs/8.x/migrations.
    */

    /* Definice tabulky (pro vytvoreni) */
    public function up(){
        Schema::create('shift_info_dimension', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('shift_info_id');
            $table->dateTime('shift_start')->nullable();
            $table->dateTime('shift_end')->nullable();
            $table->dateTime('attendance_check_in')->nullable();
            $table->dateTime('attendance_check_out')->nullable();
            $table->dateTime('attendance_check_in_company')->nullable();
            $table->dateTime('attendance_check_out_company')->nullable();
        });
    }

    /* Odstraneni tabulky */
    public function down(){
        Schema::dropIfExists('shift_info_dimension');
    }
}
