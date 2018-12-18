<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_admin_id')->unsigned()->nullable();
            $table->foreign('created_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->string('email')->index();
            $table->string('subject');
            $table->text('message');
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
        Schema::dropIfExists('email_messages');
    }
}
