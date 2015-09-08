<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMergeSynonymSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synonym_merge', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('synonym_id')->unsigned()->index();
            // The id of the synonym to be merged
            $table->integer('merge_id')->unsigned()->index();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(500);
            $table->timestamps();
            
            $table->foreign('synonym_id')->references('id')->on('synonyms')->onDelete('cascade');
            $table->foreign('merge_id')->references('id')->on('synonyms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');            
            $table->foreign('status_id')->references('id')->on('statuses');
            
            $table->unique(['synonym_id', 'merge_id'], 'synonym_merge_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('synonym_merge');
    }
}
