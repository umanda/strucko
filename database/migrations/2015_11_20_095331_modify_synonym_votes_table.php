<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySynonymVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('synonym_votes', function (Blueprint $table) {
            
            $table->dropForeign('synonym_votes_synonym_id_foreign');
            $table->dropForeign('synonym_votes_term_id_foreign');
            
            $table->dropIndex('synonym_votes_synonym_id_foreign');
            
            $table->dropUnique('synonym_votes_term_id_synonym_id_user_id_unique');
            
            $table->dropColumn(['term_id', 'synonym_id', 'vote']);
        });
        
        Schema::table('synonym_votes', function (Blueprint $table) {
            
            $table->integer('synonym_id')->unsigned();
            $table->boolean('is_positive');
            
            $table->foreign('synonym_id')->references('id')->on('synonyms')->onDelete('cascade');
            
            $table->unique(['synonym_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('synonym_votes', function (Blueprint $table) {
            
            $table->dropForeign('synonym_votes_synonym_id_foreign');
            
            $table->dropUnique('synonym_votes_synonym_id_user_id_unique');
            
            $table->dropColumn(['synonym_id', 'is_positive']);
            
        });
        
        Schema::table('synonym_votes', function (Blueprint $table) {
            $table->integer('term_id')->unsigned();
            $table->integer('synonym_id')->unsigned();
            $table->integer('vote');
            
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('synonym_id')->references('id')->on('terms')->onDelete('cascade');
            $table->unique(['term_id', 'synonym_id', 'user_id']);
            
        });
    }
}
