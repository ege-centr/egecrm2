<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColimnsAfterTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['head_teacher_id', 'school']);
        });
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['head_teacher_id', 'created_at', 'updated_at']);
        });
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['expected_score_from', 'expected_score_to', 'expected_score_max']);
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
