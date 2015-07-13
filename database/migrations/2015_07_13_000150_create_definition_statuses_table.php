<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefinitionStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definition_statuses', function (Blueprint $table) {
            $table->increments('id');
            // Name of the status
            $table->string('definition_status');
            // Rank of the status (or order)
            $table->integer('rank');
            // Is the status active
            $table->boolean('active')->default(1);
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
        Schema::drop('definition_statuses');
    }
}
