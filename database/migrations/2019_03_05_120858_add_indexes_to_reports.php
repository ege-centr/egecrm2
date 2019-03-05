<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->index(['client_id', 'teacher_id', 'subject_id', 'year']);
            $table->index('date');
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->index(['entity_id', 'teacher_id', 'subject_id', 'year']);
            $table->index('date');
            $table->index('teacher_id');
            $table->index('entity_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            //
        });
    }
}
