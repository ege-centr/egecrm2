<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Admin\Admin, Admin\AdminIp, Email, Phone};
use DB;

class AdminNicknames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:admin-nicknames';

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
        $admins = Admin::all();
        $bar = $this->output->createProgressBar(count($admins));

        foreach ($admins as $admin) {
            Admin::whereId($admin->id)->update([
                'nickname' => dbEgecrm('admins')->whereId($admin->id)->value('login')
            ]);
            $bar->advance();
        }
        $bar->finish();
    }
}
