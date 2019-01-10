<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestProblemAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_problem_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('text');
            $table->smallInteger('score')->default(0)->unsigned();
            // $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('test_problem_id');
            $table->foreign('test_problem_id')->references('id')->on('test_problems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_problem_answers');
    }
}
