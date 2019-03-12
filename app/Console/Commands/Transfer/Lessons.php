<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Teacher, Client\Client};
use DB;

class Lessons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:lessons {take}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $take = $this->argument('take');
        DB::table('lessons')->delete();
        DB::table('client_lessons')->delete();

        $egecrm_items = dbEgecrm('visit_journal')
            ->where('type_entity', '<>', 'STUDENT')
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take)->orderBy('id', 'desc');
            })
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            $group_id = DB::table('groups')->where('old_group_id', $item->id_group)->value('id');
            if ($group_id) {
                $status = ($item->cancelled ? 'cancelled' : ($item->type_entity ? 'conducted' : 'planned'));
                $lessonId = DB::table('lessons')->insertGetId([
                    'teacher_id' => $item->id_teacher,
                    'price' => $item->price,
                    'group_id' => $group_id,
                    'date' => $item->lesson_date,
                    'time' => $item->lesson_time,
                    'cabinet_id' => $item->cabinet,
                    'status' => $status,
                    'is_unplanned' => false,
                    'conducted_email_id' => 69,
                    'conducted_at' => ($item->cancelled ? null : $item->date),
                    'created_at' => $item->type_entity ? $item->date : now()->format(DATE_FORMAT),
                    'updated_at' => $item->type_entity ? $item->date : now()->format(DATE_FORMAT),
                ]);

                // для проведенных занятий
                // отмечаем учеников
                if ($status === 'conducted') {
                    $clientLessons = dbEgecrm('visit_journal')
                        ->where('type_entity', 'STUDENT')
                        ->where('id_group', $item->id_group)
                        ->where('lesson_date', $item->lesson_date)
                        ->where('lesson_time', $item->lesson_time)
                        ->get();

                    foreach($clientLessons as $clientLesson) {
                        $client_id = DB::table('clients')->where('old_student_id', $clientLesson->id_entity)->value('id');
                        if ($client_id) {
                            DB::table('client_lessons')->insert([
                                'client_id' => $client_id,
                                'lesson_id' => $lessonId,
                                'grade_id' => $clientLesson->grade,
                                'late' =>  $clientLesson->late,
                                'comment' => $clientLesson->comment ?: '',
                                'is_absent' => $clientLesson->presence == 1 ? false : true,
                                'price' => $clientLesson->price,
                            ]);
                        }
                    }
                }
            }
            $bar->advance();
        }
        $bar->finish();
        // $this->info("Setting entry_id...");
        // $this->setEntryId();
    }

    // public function setEntryId()
    // {
    //       // проставить entry_id
    //       $lessons = DB::table('lessons')->where('entity_type', Teacher::class)->get();
    //       foreach ($lessons as $lesson) {
    //           $entry_id = uniqid();
    //           DB::table('lessons')->whereId($lesson->id)->update(compact('entry_id'));
    //           DB::table('lessons')->where([
    //               ['entity_type', Client::class],
    //               ['date', $lesson->date],
    //               ['time', $lesson->time],
    //               ['group_id', $lesson->group_id],
    //           ])->update(compact('entry_id'));
    //       }

    //       // проставить entry_id в запланированных
    //       $lessons = DB::table('lessons')->whereNull('entity_type')->get();
    //       foreach ($lessons as $lesson) {
    //           $entry_id = uniqid();
    //           DB::table('lessons')->whereId($lesson->id)->update(compact('entry_id'));
    //       }
    // }
}
