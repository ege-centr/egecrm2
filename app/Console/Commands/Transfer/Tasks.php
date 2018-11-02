<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Task};
use DB;

class Tasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:tasks {take}';

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
        DB::table('tasks')->delete();
        DB::table('comments')->where('entity_type', Task::class)->delete();

        $egecrm_items = dbEgecrm('tasks')
            ->whereNotNull('html')
            ->where('html', '<>', '')
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take)->orderBy('id', 'desc');
            })
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Task::create((array)$egecrm_item);
            $id = DB::table('tasks')->insertGetId([
                'text' => $item->html,
                'status' => $this->getStatus($item->id_status),
                'created_admin_id' => $item->id_user,
                'responsible_admin_id' => $item->id_user_responsible ?: null,
                'created_at' => $item->date_created,
                'updated_at' => $item->date_created,
            ]);

            // Comments
            $comments = dbEgecrm('comments')->where('place', 'TASK')->where('id_place', $item->id)->get();
            foreach($comments as $comment) {
                DB::table('comments')->insert([
                    'created_admin_id' => $comment->id_user,
                    'text' => $comment->comment,
                    'entity_type' => Task::class,
                    'entity_id' => $id,
                    'created_at' => $comment->date,
                    'updated_at' => $comment->date,
                ]);
            }

            $bar->advance();
        }
        $bar->finish();
    }

    public function getStatus($value)
    {
        // const NEWR		       	    = 1;
		// const UPLOADED_PRODUCTION	= 5;
		// const FINISHED		        = 6;
		// const DEBUG 		        = 7;
		// const CLOSED 	 	        = 8;
        switch($value) {
            case 1: return 'new';
            case 5: return 'testing';
            case 6: return 'done';
            case 7: return 'debug';
            default: return 'closed';
        }
    }
}
