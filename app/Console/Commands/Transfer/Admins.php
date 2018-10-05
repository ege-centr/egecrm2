<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Admin, AdminIp, Email, Phone};
use DB;

class Admins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:admins';

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
        DB::table('admins')->delete();
        DB::table('admin_ips')->delete();
        Email::where('entity_type', Admin::class)->delete();
        Phone::where('entity_type', Admin::class)->delete();

        $egecrm_admins = dbEgecrm('admins')->get();

        $bar = $this->output->createProgressBar(count($egecrm_admins));
        foreach($egecrm_admins as $admin) {
            $new_admin = Admin::create((array)$admin);

            // переносим email
            $user = dbEgecrm('users')
                ->where('type', 'ADMIN')
                ->where('id_entity', $admin->id)
                ->first();

            if ($user && $user->email) {
                $new_admin->email()->create(['email' => $user->email]);
            } else {
                $new_admin->email()->create(['email' => 'empty@email.ru']);
            }

            // переносим admin ips
            $admin_ips = dbEgecrm('admin_ips')->where('id_admin', $admin->id)->get();
            if (count($admin_ips)) {
                $new_admin->ips()->createMany($admin_ips->map(function($e) {
                    return (array)$e;
                })->all());
            }

            // переносим челефон
            if ($user->phone) {
                $new_admin->phones()->create(['phone' => $user->phone]);
            }

            $bar->advance();
        }
        $bar->finish();
    }
}
