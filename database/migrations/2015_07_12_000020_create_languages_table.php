<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            // The three-letter 639-3 identifier
            $table->char('id', 3);
            // Equivalent 639-2 identifier of the bibliographic applications code set, if there is one
            $table->char('part2b', 3)->nullable();
            // Equivalent 639-2 identifier of the terminology applications code set, if there is one
            $table->char('part2t', 3)->nullable();
            // Equivalent 639-1 identifier, if there is one
            $table->char('part1', 2)->nullable();
            // Scope: I(ndividual), M(acrolanguage), S(pecial)
            $table->enum('scope', ['I', 'M', 'S']);
            // Type: A(ncient), C(onstructed), E(xtinct), H(istorical), L(iving), S(pecial)
            $table->enum('type', ['A', 'C', 'E', 'H', 'L', 'S']);
            // Reference language name
            $table->string('ref_name');
            // Comment relating to one or more of the columns
            $table->string('comment')->nullable();
            // Is the Language available to choose
            $table->boolean('active')->default(0);
            $table->timestamps();
            
            // Locale option to use with the language. See setlocale() in php manual.
            $table->string('locale')->nullable();
            
            // Indexes
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('languages');
    }
}
