<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMergeSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merge_suggestions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('term_id')->unsigned();
            // Term can change concepts
            $table->integer('concept_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(500);
            // The sum of votes in the merge_suggestion_votes table.
            $table->integer('votes_sum')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('concept_id')->references('id')->on('concepts')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merge_suggestions');
    }
}
