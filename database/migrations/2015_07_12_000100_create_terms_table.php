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
            $table->string('menu_letter', 30);
            $table->timestamps();
            
            // Attributes - foreign keys
            $table->integer('synonym_id')->unsigned();
            $table->integer('user_id')->unsigned();
            // Default status will be "Suggested".
            $table->integer('status_id')->unsigned()->default(500);
            
            // Foreign keys - constraints
            $table->foreign('synonym_id')->references('id')->on('synonyms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
            
            // Unique constraints - one term per language, part of speech and field. 
            $table->unique(['term', 'synonym_id'], 'terms_unique');
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
