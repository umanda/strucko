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
            $table->string('abbreviation', 30)->nullable();
            $table->string('slug');
            $table->string('slug_unique');
            $table->timestamps();
            
            // Attributes - foreign keys
            $table->integer('synonym_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->char('language_id', 3);
            $table->integer('status_id')->unsigned()->default(500);
            $table->integer('part_of_speech_id')->unsigned();
            $table->integer('scientific_branch_id')->unsigned();
            
            // Foreign keys - constraints
            $table->foreign('synonym_id')->references('id')->on('synonyms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('part_of_speech_id')->references('id')->on('part_of_speeches');
            $table->foreign('scientific_branch_id')->references('id')->on('scientific_branches');
            
            // Unique constraints - one term per language, part of speech and 
            // category (scientific branch). 
            $table->unique(['term', 'language_id', 'part_of_speech_id', 'scientific_branch_id'], 'terms_unique');
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
