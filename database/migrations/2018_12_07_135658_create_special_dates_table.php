<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['exam', 'vacation']);
            $table->smallInteger('year')->unsigned();
            $table->date('date');
            $table->smallInteger('subject_id')->unsigned()->nullable();
            $table->smallInteger('grade_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_dates');
    }
}
