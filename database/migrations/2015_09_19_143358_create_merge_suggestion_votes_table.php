<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMergeSuggestionVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merge_suggestion_votes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('merge_suggestion_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('is_positive');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('merge_suggestion_id')->references('id')->on('merge_suggestions')->onDelete('cascade');
            
            // Unique constraint for one term per language, part of speech and field. 
            $table->unique(['merge_suggestion_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merge_suggestion_votes');
    }
}
