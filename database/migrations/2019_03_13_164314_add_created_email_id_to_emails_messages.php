<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedEmailIdToEmailsMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_messages', function (Blueprint $table) {
            $table->dropForeign('email_messages_created_admin_id_foreign');
        });

        Schema::table('email_messages', function (Blueprint $table) {
            $table->dropColumn('created_admin_id');
        });

        Schema::table('email_messages', function (Blueprint $table) {
           $table->unsignedInteger('created_email_id')->nullable();
        });

        \DB::table('email_messages')->update(['created_email_id' => 69]);

        Schema::table('email_messages', function (Blueprint $table) {
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_messages', function (Blueprint $table) {
            //
        });
    }
}
