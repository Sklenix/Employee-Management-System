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
            $table->id('company_id');
            $table->string('company_name');
            $table->string('company_first_name');
            $table->string('company_surname');
            $table->string('company_email')->unique();
            $table->string('company_phone')->nullable();
            $table->string('company_login')->unique();
            $table->timestamp('company_email_verified_at')->nullable();
            $table->string('company_password');
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
        Schema::dropIfExists('companies');
    }
}
