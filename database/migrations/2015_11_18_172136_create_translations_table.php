<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('term_id')->unsigned();
            $table->integer('translation_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(500);
            // The sum of votes in the merge_suggestion_votes table.
            $table->integer('votes_sum')->default(0);
            $table->timestamps();
            
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('translation_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            
            $table->unique(['term_id', 'translation_id', 'user_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('translations');
    }
}
