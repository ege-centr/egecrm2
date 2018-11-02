<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Teacher, Client\Client};
use DB;

class Journal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:journal {take}';

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
        DB::table('journal')->delete();

        $egecrm_items = dbEgecrm('visit_journal')
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take);
            })
            ->orderBy('id', 'desc')
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Group::create((array)$egecrm_item);
            if ($item->type_entity == 'STUDENT') {
                $client_id = DB::table('clients')->where('old_student_id', $item->id_entity)->value('id');
            }
            $group_id = DB::table('groups')->where('old_group_id', $item->id_group)->value('id');;
            if ($group_id && ($item->type_entity == 'TEACHER' || ($item->type_entity == 'STUDENT' && $client_id))) {
                $id = DB::table('journal')->insertGetId([
                    'teacher_id' => $item->id_teacher,
                    'subject_id' => $item->id_subject,
                    'price' => $item->price,
                    'duration' => $item->duration,
                    'year' => $item->year,
                    'entity_type' => $item->type_entity == 'TEACHER' ? Teacher::class : Client::class,
                    'entity_id' => $item->type_entity == 'STUDENT' ? $client_id : $item->id_entity,
                    'group_id' => $group_id,
                    'lesson_date' => $item->lesson_date,
                    'lesson_time' => $item->lesson_time,
                    'cabinet_id' => $item->cabinet,
                    'group_grade_id' => $item->grade,
                    'client_grade_id' => $item->grade,
                    'late' => $item->type_entity == 'STUDENT' ? $item->late : null,
                    'comment' => $item->comment ?: '',
                    'is_absent' => $item->type_entity == 'STUDENT' ? ($item->presence == 1 ? false : true) : null,
                    'is_cancelled' => $item->cancelled,
                    'is_unplanned' => false,
                    'created_email_id' => 69,
                    'created_at' => $item->date,
                    'updated_at' => $item->date,
                ]);
            }


            $bar->advance();
        }
        $bar->finish();
    }
}
