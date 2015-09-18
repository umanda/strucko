<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            
            // Attributes
            $table->increments('id');
            $table->string('term');
            $table->string('slug')->unique();
            $table->string('menu_letter', 30);
            // Sum of votes in term_votes table
            $table->integer('votes_sum')->default(0);
            $table->timestamps();
            
            // Attributes - foreign keys
            $table->integer('concept_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->char('language_id', 3);
            $table->integer('part_of_speech_id')->unsigned();
            $table->integer('scientific_field_id')->unsigned();
            // Default status will be "Suggested".
            $table->integer('status_id')->unsigned()->default(500);
            
            // Foreign keys - constraints
            $table->foreign('concept_id')->references('id')->on('concepts');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('part_of_speech_id')->references('id')->on('part_of_speeches');
            $table->foreign('scientific_field_id')->references('id')->on('scientific_fields');
            
            // Unique constraint for one term per language, part of speech and field. 
            $table->unique(['term', 'language_id', 'part_of_speech_id', 'scientific_field_id'], 'terms_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('terms');
    }
}
