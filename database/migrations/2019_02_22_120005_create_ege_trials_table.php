<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEgeTrialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ege_trials', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unsignedInteger('sum');
            $table->date('date');
            $table->smallInteger('subject_id')->unsigned()->nullable();
            $table->smallInteger('grade_id')->unsigned()->nullable();
            $table->smallInteger('score')->unsigned()->nullable();
            $table->smallInteger('max_score')->unsigned()->nullable();
            $table->unsignedSmallInteger('year')->nullable()->default(null);
            $table->string('description')->nullable();
            $table->integer('created_admin_id')->unsigned()->nullable();
            $table->foreign('created_admin_id')->references('id')->on('admins')->onDelete('set null');
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
        Schema::dropIfExists('ege_trials');
    }
}
