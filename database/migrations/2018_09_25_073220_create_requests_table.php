<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('status', ['new', 'awaiting', 'finished'])->default('new');

            $table->integer('responsible_user_id')->unsigned()->nullable();
            $table->foreign('responsible_user_id')->references('id')->on('users')->onDelete('set null');

            $table->integer('created_user_id')->unsigned()->nullable();
            $table->foreign('created_user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('google_id')->nullable()->default(null);
            $table->string('name')->default('');

            $table->string('branches')->default('');
            $table->string('subjects')->default('');
            $table->string('comment', 1000)->default('');

            $table->unsignedTinyInteger('grade')->nullable()->default(null);

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
        Schema::dropIfExists('requests');
    }
}
