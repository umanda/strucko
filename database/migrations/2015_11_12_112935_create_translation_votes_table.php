<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translation_votes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('term_id')->unsigned();
            $table->integer('translation_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('vote');
            $table->timestamps();
            
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('translation_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Unique constraint for one vote per term and its translation. 
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
        Schema::drop('translation_votes');
    }
}
