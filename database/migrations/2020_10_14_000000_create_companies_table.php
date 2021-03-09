<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_companies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('company_id');
            $table->string('company_name');
            $table->string('company_user_name');
            $table->string('company_user_surname');
            $table->string('email')->unique();
            $table->string('company_phone')->nullable();
            $table->string('company_login')->unique();
            $table->string('company_url')->unique();
            $table->string('company_picture')->nullable();
            $table->string('password');
            $table->string('company_ico')->nullable();
            $table->string('company_city')->nullable();;
            $table->string('company_street')->nullable();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('table_companies');
    }
}
