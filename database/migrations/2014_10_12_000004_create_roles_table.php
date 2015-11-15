<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->unsigned()->primary();
            // Name of the status
            $table->string('role');
            // The weight of the vote for the user role. 
            $table->smallInteger('vote_weight');
            // The spam thershold used to stop users suggesting too many terms and definitions.
            $table->integer('spam_threshold');
            // Is the status active
            $table->boolean('active')->default(1);
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
        Schema::drop('roles');
    }
}
