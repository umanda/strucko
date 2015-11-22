<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTermIdToDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('definitions', function (Blueprint $table) {
            $table->integer('term_id')->unsigned()->nullable();
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('definitions', function (Blueprint $table) {
            
            $table->dropForeign('definitions_term_id_foreign');
            $table->dropIndex('definitions_term_id_foreign');
            $table->dropColumn(['term_id']);
        });
    }
}
