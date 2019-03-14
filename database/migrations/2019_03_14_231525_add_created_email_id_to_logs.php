<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedEmailIdToLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropColumn('email_id');
        });

        Schema::table('logs', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');

            $table->unsignedInteger('preview_mode_email_id')->nullable();
            $table->foreign('preview_mode_email_id')->references('id')->on('emails')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            //
        });
    }
}
