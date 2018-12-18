<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('sms');
        Schema::create('sms', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('created_admin_id')->unsigned()->nullable();
            $table->foreign('created_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->boolean('is_secret')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms', function (Blueprint $table) {
            //
        });
    }
}
