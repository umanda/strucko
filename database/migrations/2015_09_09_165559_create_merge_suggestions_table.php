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
            $table->integer('synonym_id')->unsigned()->index();
            // The id of the synonym to be merged
            $table->integer('merge_id')->unsigned()->index();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(500);
            
            $table->foreign('synonym_id')->references('id')->on('synonyms')->onDelete('cascade');
            $table->foreign('merge_id')->references('id')->on('synonyms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');            
            $table->foreign('status_id')->references('id')->on('statuses');
            
            $table->unique(['synonym_id', 'merge_id'], 'synonym_merges_unique');
            $table->timestamps();
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
