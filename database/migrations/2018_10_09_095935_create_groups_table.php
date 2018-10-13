<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_id')->nullable()->default(null)->index();
            $table->unsignedInteger('head_teacher_id')->nullable()->default(null)->index();
            $table->smallInteger('subject_id')->unsigned()->nullable()->default(null);
            $table->smallInteger('grade_id')->unsigned()->nullable()->default(null);
            $table->smallInteger('teacher_price')->unsigned()->nullable()->default(null);
            $table->smallInteger('duration')->unsigned()->nullable()->default(135);
            $table->smallInteger('year')->unsigned()->nullable()->index();
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_ready_to_start')->default(false);
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
        Schema::dropIfExists('groups');
    }
}
