<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('text');
            $table->enum('status', ['new', 'testing', 'done', 'debug', 'closed'])->default('new');

            $table->unsignedInteger('responsible_admin_id')->nullable();
            $table->foreign('responsible_admin_id')->references('id')->on('admins')->onDelete('set null');

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
        Schema::dropIfExists('tasks');
    }
}
