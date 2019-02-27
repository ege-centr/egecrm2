<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->smallInteger('subject_id')->unsigned()->nullable();
            $table->smallInteger('grade_id')->unsigned()->nullable();
            $table->string('signature');
            $table->string('expressive_title', 1000);
            $table->smallInteger('score')->unsigned()->nullable();
            $table->smallInteger('max_score')->unsigned()->nullable();
            $table->smallInteger('year')->unsigned()->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unique(['teacher_id', 'client_id', 'grade_id', 'subject_id', 'year']);
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
        Schema::dropIfExists('reviews');
    }
}
