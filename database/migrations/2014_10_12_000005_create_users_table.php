<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            // User has to verify it's email
            $table->boolean('verified')->default(false);
            // User can be banned
            $table->boolean('banned')->default(false);
            // Token used to verify email
            $table->string('token')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // The default role of the user will be "Registered user".
            $table->integer('role_id')->unsigned()->default(500);
            
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
