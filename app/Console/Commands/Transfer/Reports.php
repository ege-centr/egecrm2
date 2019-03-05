<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\Review;
use DB;

class Reports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:reports {take}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer reports';

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
        DB::table('reports')->delete();

        $egecrm_items = dbEgecrm('reports')
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take)->orderBy('id', 'desc');
            })
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        $inserted = 0;

        foreach($egecrm_items as $item) {
            // $new_item = Task::create((array)$egecrm_item);
            $client_id = DB::table('clients')->where('old_student_id', $item->id_student)->value('id');

            if ($client_id) {
                $id = DB::table('reports')->insertGetId([
                    'teacher_id' => $item->id_teacher,
                    'client_id' => $client_id,
                    'subject_id' => $item->id_subject,
                    'year' => $item->year,
                    'is_available_for_parents' => $item->available_for_parents,
                    'homework_score' => $item->homework_grade,
                    'homework_comment' => $item->homework_comment,
                    'activity_score' => $item->activity_grade,
                    'activity_comment' => $item->activity_comment,
                    'behavior_score' => $item->behavior_grade,
                    'behavior_comment' => $item->behavior_comment,
                    'learning_ability_score' => $item->material_grade,
                    'learning_ability_comment' => $item->material_comment,
                    'knowledge_score' => $item->tests_grade,
                    'knowledge_comment' => $item->tests_comment,
                    'expected_score_from' => $item->expected_score_from,
                    'expected_score_to' => $item->expected_score_to,
                    'expected_score_max' => $item->expected_score_total,
                    'recommendation' => $item->recommendation,
                    'date' => $item->date,
                    'created_email_id' => null,
                    'created_at' => now()->format(DATE_TIME_FORMAT),
                    'updated_at' => now()->format(DATE_TIME_FORMAT),
                ]);
                $inserted++;
            }

            $bar->advance();
        }
        $bar->finish();
        $this->info("\n{$inserted} rows inserted");
    }

    // private function getRating($rating)
    // {
    //     if ($rating == 6) {
    //         return -1;
    //     }
    //     return $rating > 0 ? $rating : null;
    // }
}
