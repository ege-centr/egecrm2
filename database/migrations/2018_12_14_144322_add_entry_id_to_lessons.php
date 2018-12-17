<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntryIdToLessons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('entry_id', 100)->index();
        });

        // проставить entry_id
        $lessons = \DB::table('lessons')->where('entity_type', \App\Models\Teacher::class)->get();
        foreach ($lessons as $lesson) {
            $entry_id = uniqid();
            \DB::table('lessons')->whereId($lesson->id)->update(compact('entry_id'));
            \DB::table('lessons')->where([
                ['entity_type', \App\Models\Client\Client::class],
                ['date', $lesson->date],
                ['time', $lesson->time],
                ['group_id', $lesson->group_id],
            ])->update(compact('entry_id'));
        }

        // проставить entry_id в запланированных
        $lessons = \DB::table('lessons')->whereNull('entity_type')->get();
        foreach ($lessons as $lesson) {
            $entry_id = uniqid();
            \DB::table('lessons')->whereId($lesson->id)->update(compact('entry_id'));
        }
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
