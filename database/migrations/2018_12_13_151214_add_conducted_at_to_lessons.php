<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConductedAtToLessons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->datetime('conducted_at')->nullable()->default(null);
        });
        \DB::statement('UPDATE lessons SET conducted_at=created_at WHERE entity_type IS NOT NULL');
        \DB::statement('UPDATE lessons SET conducted_email_id=NULL WHERE entity_type IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('conducted_at');
        });
    }
}
