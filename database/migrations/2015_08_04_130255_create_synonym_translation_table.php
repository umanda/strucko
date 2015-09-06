<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSynonymTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synonym_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('synonym_id')->unsigned()->index();
            $table->integer('translation_id')->unsigned()->index();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(500);
            $table->timestamps();
            
            $table->foreign('synonym_id')->references('id')->on('synonyms')->onDelete('cascade');
            $table->foreign('translation_id')->references('id')->on('synonyms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
            
            $table->unique(['synonym_id', 'translation_id'], 'synonym_translation_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('synonym_translation');
    }
}
