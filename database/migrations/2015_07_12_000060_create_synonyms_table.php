<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSynonymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synonyms', function (Blueprint $table) {
            $table->increments('id');
            $table->char('language_id', 3);
            $table->integer('part_of_speech_id')->unsigned();
            $table->integer('scientific_field_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('part_of_speech_id')->references('id')->on('part_of_speeches');
            $table->foreign('scientific_field_id')->references('id')->on('scientific_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('synonyms');
    }
}
