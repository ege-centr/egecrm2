<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type')->nullable()->index();
            $table->unsignedInteger('entity_id')->nullable();
            $table->index(['entity_type', 'entity_id']);

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('cabinet_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable();
            $table->unsignedInteger('group_grade_id')->nullable();
            $table->unsignedInteger('client_grade_id')->nullable();
            $table->smallInteger('year')->unsigned();
            $table->smallInteger('duration')->unsigned();

            $table->smallInteger('late')->nullable();
            $table->string('comment');

            $table->smallInteger('price')->nullable();

            $table->boolean('is_absent')->default(false)->nullable();
            $table->boolean('is_cancelled')->default(false);
            $table->boolean('is_unplanned')->default(false);

            $table->date('lesson_date');
            $table->time('lesson_time');

            $table->unsignedInteger('created_email_id');
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
        Schema::dropIfExists('journal');
    }
}
