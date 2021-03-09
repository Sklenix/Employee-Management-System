<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_admin', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('admin_id');
            $table->string('admin_name');
            $table->string('admin_surname');
            $table->string('admin_email');
            $table->string('admin_login');
            $table->string('admin_password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_admin');
    }
}
