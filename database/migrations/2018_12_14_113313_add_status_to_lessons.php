<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToLessons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->enum('status', ['planned', 'conducted', 'cancelled'])->default('planned');
        });
        \DB::statement("UPDATE lessons SET `status`='conducted' WHERE entity_type IS NOT NULL AND is_cancelled=0");
        \DB::statement("UPDATE lessons SET `status`='cancelled' WHERE is_cancelled=1");
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('is_cancelled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            //
        });
    }
}
