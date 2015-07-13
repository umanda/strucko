<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('definition');
            $table->timestamps();
            
            $table->integer('synonym_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('definition_status_id')->unsigned();
            
            $table->foreign('synonym_id')->references('id')->on('synonyms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('definition_status_id')->references('id')->on('definition_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('definitions');
    }
}
