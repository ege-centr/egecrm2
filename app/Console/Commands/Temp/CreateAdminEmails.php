<?php

namespace App\Console\Commands\Temp;

use Illuminate\Console\Command;
use App\Models\{Admin\Admin, Admin\AdminIp, Email, Phone};
use DB;

class CreateAdminEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp:create-admin-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing admin emails';

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
            if ($admin->email === null) {
                $admin->email()->create([
                    'email' => 'admin–' . $admin->id . '@gmail.com'
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
