<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Admin\Admin, Admin\AdminIp, Email, Phone};
use DB;

class Admins extends TransferCommand
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
    protected $description = 'Transfer admins';

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
        $this->info("\n\nTransfering admins...");
        $this->truncate('admins');
        $this->truncate('admin_ips');
        Email::where('entity_type', Admin::class)->delete();
        Phone::where('entity_type', Admin::class)->delete();

        $egecrmAdmins = dbEgecrm('admins')->get();

        $bar = $this->output->createProgressBar(count($egecrmAdmins));
        foreach($egecrmAdmins as $admin) {
            $admin->nickname = $admin->login;
            $admin->old_id = $admin->id;
            unset($admin->salary);
            unset($admin->rights);
            $newAdmin = Admin::create((array) $admin);

            // переносим email
            $user = dbEgecrm('users')
                ->where('type', 'ADMIN')
                ->where('id_entity', $admin->id)
                ->first();

            if ($user && $user->email) {
                $email = $newAdmin->email()->create(['email' => $user->email]);
                DB::table('emails')->whereId($email->id)->update(['password' => $user->password]);
            } else {
                $newAdmin->email()->create(['email' => 'admin–' . $admin->id . '@empty.ru']);
            }

            // переносим admin ips
            $admin_ips = dbEgecrm('admin_ips')->where('id_admin', $admin->id)->get();
            if (count($admin_ips)) {
                $newAdmin->ips()->createMany($admin_ips->map(function($e) {
                    return (array) $e;
                })->all());
            }

            // переносим челефон
            if ($admin->phone) {
                $newAdmin->phones()->create(['phone' => $admin->phone]);
            }

            $bar->advance();
        }
        $bar->finish();
    }
}
