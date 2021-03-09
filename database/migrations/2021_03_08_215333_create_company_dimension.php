<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDimension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_dimension');
    }
}
