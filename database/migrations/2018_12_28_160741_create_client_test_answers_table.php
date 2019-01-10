<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_test_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('test_problem_answer_id');
            $table->foreign('test_problem_answer_id')->references('id')->on('test_problem_answers')->onDelete('cascade');
            $table->unsignedInteger('client_id')->index();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unique(['client_id', 'test_problem_answer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_test_answers');
    }
}
