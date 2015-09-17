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
            $table->text('source')->nullable();
            $table->text('link')->nullable();
            $table->timestamps();
            
            $table->integer('concept_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(500);
            $table->char('language_id', 3);
            
            $table->foreign('concept_id')->references('id')->on('concepts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('language_id')->references('id')->on('languages');
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
