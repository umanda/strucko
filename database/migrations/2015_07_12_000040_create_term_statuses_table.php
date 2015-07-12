<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_statuses', function (Blueprint $table) {
            $table->increments('id');
            // Name of the status
            $table->string('status');
            // Rank of the status (or order)
            $table->integer('rank');
            // Is the status active
            $table->boolean('active');
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
        Schema::drop('term_statuses');
    }
}
