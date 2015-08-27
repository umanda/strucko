<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('definition');
            $table->text('source')->nullable();
            $table->timestamps();
            
            $table->integer('synonym_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(500);
            
            $table->foreign('synonym_id')->references('id')->on('synonyms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('definitions');
    }
}
