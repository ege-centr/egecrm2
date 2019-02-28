<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Task};
use DB;

class Reviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:reviews {take}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer reviews';

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
        DB::table('reviews')->delete();

        $egecrm_items = dbEgecrm('teacher_reviews')
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take)->orderBy('id', 'desc');
            })
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Task::create((array)$egecrm_item);
            $client_id = DB::table('clients')->where('old_student_id', $item->id_student)->value('id');

            if ($client_id) {
                $id = DB::table('reviews')->insertGetId([
                    'teacher_id' => $item->id_teacher,
                    'client_id' => $client_id,
                    'subject_id' => $item->id_subject,
                    'grade_id' => $item->grade,
                    'year' => $item->year,
                    'signature' => $item->signature,
                    'expressive_title' => $item->expressive_title,
                    'created_at' => $item->date,
                    'updated_at' => $item->date,
                    'score' => $item->score,
                    'max_score' => $item->max_score,
                    'is_approved' => $item->approved,
                    'is_published' => $item->published,
                ]);

                DB::table('review_comments')->insert([
                    'text' => $item->comment,
                    'rating' => $this->getRating($item->rating),
                    'created_at' => $item->date,
                    'updated_at' => $item->date,
                    'type' => 'client',
                    'review_id' => $id,
                ]);

                if ($item->admin_comment || $item->admin_comment_final) {
                    DB::table('review_comments')->insert([
                        'text' => $item->admin_comment,
                        'rating' => $this->getRating($item->admin_rating),
                        'type' => 'admin',
                        'created_at' => $item->date,
                        'updated_at' => $item->date,
                        'review_id' => $id,
                    ]);
                    DB::table('review_comments')->insert([
                        'text' => $item->admin_comment_final,
                        'rating' => $this->getRating($item->admin_rating_final),
                        'type' => 'final',
                        'created_at' => $item->date,
                        'updated_at' => $item->date,
                        'review_id' => $id,
                    ]);
                }
            }

            $bar->advance();
        }
        $bar->finish();

        $this->line("\n");
        $this->info("Reviewer admin id");


        $items = dbEgecrm('students')->select('id', 'id_user_review')->where('id_user_review', '>', 0)->get();

        $bar = $this->output->createProgressBar(count($items));

        foreach($items as $item) {
            DB::table('clients')->where('old_student_id', $item->id)->update(['reviewer_admin_id' => $item->id_user_review]);
            $bar->advance();
        }

        $bar->finish();
    }

    private function getRating($rating)
    {
        if ($rating == 6) {
            return -1;
        }
        return $rating > 0 ? $rating : null;
    }
}
