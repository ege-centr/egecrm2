<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorCreatedAdminId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update comments
            join emails on (emails.entity_id = comments.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set comments.created_email_id = emails.id
        ");

        // Schema::table('comments', function (Blueprint $table) {
        //     $table->dropForeign('comments_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });



        // CONTRACTS
        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update contracts
            join emails on (emails.entity_id = contracts.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set contracts.created_email_id = emails.id
        ");

        // Schema::table('contracts', function (Blueprint $table) {
        //     $table->dropForeign('contracts_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });


        // EMAILS
        Schema::table('email_messages', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update email_messages
            join emails on (emails.entity_id = email_messages.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set email_messages.created_email_id = emails.id
        ");

        // Schema::table('email_messages', function (Blueprint $table) {
        //     $table->dropForeign('email_messages_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });


        // GROUP ACTS
        Schema::table('group_acts', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update group_acts
            join emails on (emails.entity_id = group_acts.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set group_acts.created_email_id = emails.id
        ");

        // Schema::table('group_acts', function (Blueprint $table) {
        //     $table->dropForeign('group_acts_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });


        // PAYMENT ADD
        Schema::table('payment_additionals', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update payment_additionals
            join emails on (emails.entity_id = payment_additionals.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set payment_additionals.created_email_id = emails.id
        ");

        // Schema::table('payment_additionals', function (Blueprint $table) {
        //     $table->dropForeign('payment_additionals_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });


        // PAYMENTS
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update payments
            join emails on (emails.entity_id = payments.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set payments.created_email_id = emails.id
        ");

        // Schema::table('payments', function (Blueprint $table) {
        //     $table->dropForeign('payments_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });

        // REQUESTS
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update requests
            join emails on (emails.entity_id = requests.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set requests.created_email_id = emails.id
        ");

        // Schema::table('requests', function (Blueprint $table) {
        //     $table->dropForeign('requests_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });

        // SMS
        Schema::table('sms', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update sms
            join emails on (emails.entity_id = sms.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set sms.created_email_id = emails.id
        ");

        // Schema::table('sms', function (Blueprint $table) {
        //     $table->dropForeign('sms_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });

        // Tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedInteger('created_email_id')->nullable();
            $table->foreign('created_email_id')->references('id')->on('emails')->onDelete('set null');
        });

        \DB::statement("
            update tasks
            join emails on (emails.entity_id = tasks.created_admin_id and emails.entity_type='App\\Models\\Admin\\Admin')
            set tasks.created_email_id = emails.id
        ");

        // Schema::table('tasks', function (Blueprint $table) {
        //     $table->dropForeign('tasks_created_admin_id_foreign');
        //     $table->dropColumn('created_admin_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
