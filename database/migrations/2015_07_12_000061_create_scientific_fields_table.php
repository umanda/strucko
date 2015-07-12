<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScientificFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scientific_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scientific_field');
            $table->boolean('active')->default(1);
            $table->timestamps();
            
            // Attributes - foreign keys
            $table->integer('scientific_area_id')->unsigned();
            
            // Foreign keys constraints
            $table->foreign('scientific_area_id')->references('id')->on('scientific_areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scientific_fields');
    }
}
