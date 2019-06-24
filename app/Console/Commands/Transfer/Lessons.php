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
    protected $signature = 'transfer:lessons';

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
        $this->info("\n\nTransfering lessons...");
        $this->truncate('lessons');
        $this->truncate('client_lessons');

        $egecrm_items = dbEgecrm('visit_journal')
            ->where('type_entity', '<>', 'STUDENT')
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            $group_id = DB::table('groups')->where('old_group_id', $item->id_group)->value('id');
            // в том числе в новой системы группы не создаются для доп. занятий
            // и они на этом шаге будут пропускаться - что правильно
            if ($group_id) {
                $status = ($item->cancelled ? 'cancelled' : ($item->type_entity ? 'conducted' : 'planned'));
                $conductedEmailId = null;
                if ($status === 'conducted') {
                    $conductedEmailId = DB::table('emails')
                        ->where('entity_type', Teacher::class)
                        ->where('entity_id', $item->id_teacher)
                        ->value('id');
                    if (! $conductedEmailId) {
                        $this->error('No created_email_id for id_user_saved=' . $item->id_user_saved);
                        exit;
                    }
                }
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
                    'conducted_email_id' => $conductedEmailId,
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
                                // "не переноси стоимость занятия для учеников - мы эту стоимость выставим на живую после переноса в новой системе"
                                // 'price' => $clientLesson->price,
                            ]);
                        }
                    }
                }
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
