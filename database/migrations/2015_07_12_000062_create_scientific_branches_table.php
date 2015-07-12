<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScientificBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scientific_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scientific_branch');
            $table->boolean('active')->default(1);
            $table->timestamps();
            
            $table->integer('scientific_field_id')->unsigned();
            
            $table->foreign('scientific_field_id')->references('id')->on('scientific_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scientific_branches');
    }
}
