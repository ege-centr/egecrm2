<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherFreetimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_freetime', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('weekday');
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->unsignedInteger('teacher_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_freetime');
    }
}
