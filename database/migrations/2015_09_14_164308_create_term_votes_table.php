<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('term_id')->unsigned();
            // Term can change concepts, so the vote also depends on it.
            $table->integer('concept_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('is_positive');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('concept_id')->references('id')->on('concepts')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            
            // Unique constraint for one vote per term. 
            $table->unique(['term_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('term_votes');
    }
}
