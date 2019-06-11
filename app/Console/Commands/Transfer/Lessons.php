<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Teacher, Client\Client, Admin\Admin, Email};
use DB;

class Lessons extends TransferCommand
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
        $this->truncate('lessons');
        $this->truncate('client_lessons');

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
                    'price' => $item->price ?: 0,
                    'group_id' => $group_id,
                    'date' => $item->lesson_date,
                    'time' => $item->lesson_time,
                    'cabinet_id' => $item->cabinet,
                    'status' => $status,
                    'is_unplanned' => false,
                    'duration' => $item->duration,
                    'conducted_email_id' => ($status === 'conducted' ? $this->getConductedEmailId($item->id_user_saved) : null),
                    'conducted_at' => ($status === 'conducted' ? $item->date : null),
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
    }

    private function getConductedEmailId($idUserSaved)
    {
        $user = dbEgecrm('users')->whereId($idUserSaved)->first();
        if ($user === null) {
            return null;
        }
        if ($user->type === 'ADMIN') {
            $entityType = Admin::class;
            $entityId = $this->getAdminId($user->id_entity);
        } else {
            $entityType = Teacher::class;
            $entityId = $user->id_entity;
        }
        return Email::where('entity_type', $entityType)->where('entity_id', $entityId)->first()->id;
    }
}
