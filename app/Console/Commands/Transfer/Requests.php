<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Request, Phone};
use DB;

class Requests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:requests {take}';

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
        DB::table('requests')->delete();
        DB::table('phones')->where('entity_type', Request::class)->delete();
        DB::table('comments')->where('entity_type', Request::class)->delete();

        $egecrm_items = dbEgecrm('requests')
            ->where('id_status', '<>', 4)
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take)->orderBy('id', 'desc');
            })
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Request::create((array)$egecrm_item);
            $id = DB::table('requests')->insertGetId([
                'status' => $this->getStatus($item->id_status),
                'responsible_admin_id' => $item->id_user ?: null,
                'created_admin_id' => $item->id_user_created ?: null,
                'google_id' => $item->id_google,
                'name' => $item->name ?: '',
                'branches' => $item->branches,
                'subjects' => $item->subjects,
                'comment' => $item->comment ?: '',
                'grade' => $item->grade,
                'created_at' => $item->date,
                'updated_at' => $item->date
            ]);

            foreach(['phone', 'phone2', 'phone3'] as $field) {
                $phone = $item->{$field};
                if ($phone) {
                    DB::table('phones')->insert([
                        'phone' => \App\Utils\Phone::clean($phone),
                        'entity_type' => Request::class,
                        'entity_id' => $id
                    ]);
                }
            }

            // Comments
            $comments = dbEgecrm('comments')->where('place', 'REQUEST')->where('id_place', $item->id)->get();
            foreach($comments as $comment) {
                DB::table('comments')->insert([
                    'created_admin_id' => $comment->id_user,
                    'text' => $comment->comment,
                    'entity_type' => Request::class,
                    'entity_id' => $id,
                    'created_at' => $comment->date,
                    'updated_at' => $comment->date,
                ]);
            }

            $bar->advance();
        }
        $bar->finish();
    }

    public function getStatus($old_status)
    {
		// const NEWR			= 0;
		// const AWAITING		= 3;
		// const COURSES 		= 9;
		// const FINISHED		= 2;
		// const DENY			= 5;
		// const SPAM			= 4;
		// const DUPLICATE		= 7;
		// const ALL 			= 8;
        switch($old_status) {
            case 3: case 9: return 'awaiting';
            case 2: case 7: case 5: return 'finished';
            default: return 'new';
        }
    }
}
