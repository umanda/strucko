<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTranslationVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translation_votes', function (Blueprint $table) {
            
            $table->dropForeign('translation_votes_translation_id_foreign');
            $table->dropForeign('translation_votes_term_id_foreign');
            
            $table->dropIndex('translation_votes_translation_id_foreign');
            
            $table->dropUnique('translation_votes_term_id_translation_id_user_id_unique');
            
            $table->dropColumn(['term_id', 'translation_id', 'vote']);
        });
        
        Schema::table('translation_votes', function (Blueprint $table) {
            
            $table->integer('translation_id')->unsigned();
            $table->boolean('is_positive');
            
            $table->foreign('translation_id')->references('id')->on('translations')->onDelete('cascade');
            
            $table->unique(['translation_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('translation_votes', function (Blueprint $table) {
            
            $table->dropForeign('translation_votes_translation_id_foreign');
            
            $table->dropUnique('translation_votes_translation_id_user_id_unique');
            
            $table->dropColumn(['translation_id', 'is_positive']);
            
        });
        
        Schema::table('translation_votes', function (Blueprint $table) {
            $table->integer('term_id')->unsigned();
            $table->integer('translation_id')->unsigned();
            $table->integer('vote');
            
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            $table->foreign('translation_id')->references('id')->on('terms')->onDelete('cascade');
            $table->unique(['term_id', 'translation_id', 'user_id']);           
            
        });
    }
}
