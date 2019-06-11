<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Group\Group, Group\GroupClient, Phone};
use DB;

class Groups extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:groups {take}';

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
        $this->truncate('groups');
        $this->truncate('group_clients');
        $this->truncateByEntity('comments', Group::class);

        $egecrm_items = dbEgecrm('groups')
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take)->orderBy('id', 'desc');
            })
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Group::create((array)$egecrm_item);
            $id = DB::table('groups')->insertGetId([
                'teacher_id' => $item->id_teacher,
                'head_teacher_id' => $item->id_head_teacher,
                'subject_id' => $item->id_subject,
                'grade_id' => $item->grade,
                'year' => $item->year,
                'is_archived' => $item->ended,
                'is_ready_to_start' => $item->ready_to_start,
                'level' => $this->getLevel($item->level),
                'old_group_id' => $item->id,
                'is_contract_signed' => $item->contract_signed,
            ]);

            $students = explode(',', $item->students);

            foreach($students as $student_id) {
                $client_id = DB::table('clients')->where('old_student_id', $student_id)->value('id');
                if ($client_id) {
                    DB::table('group_clients')->insert([
                        'group_id' => $id,
                        'client_id' => $client_id
                    ]);
                }
            }

            // Comments
            $comments = dbEgecrm('comments')->where('place', 'GROUP')->where('id_place', $item->id)->get();
            foreach($comments as $comment) {
                DB::table('comments')->insert([
                    'created_email_id' => $this->getCreatedEmailId($comment->id_user),
                    'text' => $comment->comment,
                    'entity_type' => Group::class,
                    'entity_id' => $id,
                    'created_at' => $comment->date,
                    'updated_at' => $comment->date,
                ]);
            }

            $bar->advance();
        }
        $bar->finish();
    }

    public function getLevel($value)
    {
        switch($value) {
            case 1: return 'low';
            case 2: return 'mid';
            case 3: return 'high';
            case 4: return 'special';
            default: return null;
        }
    }

    public function getAdminId($value)
    {
        // TODO: проверить связи
        if (\App\Models\Admin\Admin::whereId($value)->exists()) {
            return $value;
        }
        return 69;
    }
}
