<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->unsignedInteger('cabinet_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable()->index();
            $table->smallInteger('price')->nullable();
            $table->boolean('is_unplanned')->default(false);
            $table->enum('status', ['planned', 'conducted', 'cancelled'])->default('planned');
            $table->date('date')->index();
            $table->time('time')->nullable();
            $table->unsignedInteger('conducted_email_id')->nullable();
            $table->foreign('conducted_email_id')->references('id')->on('emails')->onDelete('set null');
            $table->datetime('conducted_at')->nullable();
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
        Schema::dropIfExists('lessons');
    }
}
