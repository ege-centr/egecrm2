<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->smallInteger('subject_id')->unsigned()->nullable();
            $table->smallInteger('year')->unsigned()->nullable();

            $table->smallInteger('homework_score')->unsigned()->nullable();
            $table->mediumText('homework_comment')->nullable();

            $table->smallInteger('activity_score')->unsigned()->nullable();
            $table->mediumText('activity_comment')->nullable();

            $table->smallInteger('behavior_score')->unsigned()->nullable();
            $table->mediumText('behavior_comment')->nullable();

            $table->smallInteger('learning_ability_score')->unsigned()->nullable();
            $table->mediumText('learning_ability_comment')->nullable();

            $table->smallInteger('knowledge_score')->unsigned()->nullable();
            $table->mediumText('knowledge_comment')->nullable();

            $table->mediumText('recommendation')->nullable();

            $table->smallInteger('expected_score_from')->unsigned()->nullable();
            $table->smallInteger('expected_score_to')->unsigned()->nullable();
            $table->smallInteger('expected_score_max')->unsigned()->nullable();

            $table->boolean('is_available_for_parents')->default(false);

            $table->date('date')->nullable();

            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails');

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
        Schema::dropIfExists('reports');
    }
}

// id_student	int(11) NULL
// id_teacher	int(11) NULL
// id_subject	int(11) NULL
// homework_grade	int(11) NULL
// homework_comment	mediumtext NULL
// activity_grade	int(11) NULL
// activity_comment	mediumtext NULL
// behavior_grade	int(11) NULL
// behavior_comment	mediumtext NULL
// material_grade	int(11) NULL
// tests_grade	int(11) NULL
// material_comment	mediumtext NULL
// tests_comment	mediumtext NULL
// date	date NULL
// available_for_parents	tinyint(1) [0]
// expected_score_from	int(11) NULL
// expected_score_to	int(11) NULL
// expected_score_total	int(11) NULL
// recommendation	mediumtext NULL
// year	int(11) [2015]
