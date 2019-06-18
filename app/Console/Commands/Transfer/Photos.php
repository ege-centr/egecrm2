<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Client\Client, Admin\Admin, Photo};
use DB;

class Photos extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:photos';

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
        $this->info("\n\nTransfering photos...");
        $this->truncate('photos');

        $this->info("Client photos...");
        $egecrm_items = dbEgecrm('students')->whereIn('photo_extension', ['jpeg', 'jpg', 'png'])->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            $clientId = DB::table('clients')->where('old_student_id', $item->id)->value('id');
            $photo = Photo::create([
                'entity_type' => Client::class,
                'entity_id' => $clientId
            ]);
            try {
                file_put_contents(
                    storage_path('app/public' . Photo::UPLOAD_PATH) . $photo->id . '_original.jpg',
                    fopen("https://lk.ege-centr.ru/img/students/{$item->id}_original.{$item->photo_extension}", 'r')
                );
            } catch (\Exception $e) {
                $photo->delete();
            }
            $bar->advance();
        }
        $bar->finish();


        $this->info("Admin photos...");
        $egecrm_items = dbEgecrm('admins')->whereIn('photo_extension', ['jpeg', 'jpg', 'png'])->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            $photo = Photo::create([
                'entity_type' => Admin::class,
                'entity_id' => $this->getAdminId($item->id)
            ]);
            try {
                file_put_contents(
                    storage_path('app/public' . Photo::UPLOAD_PATH) . $photo->id . '_original.jpg',
                    fopen("https://lk.ege-centr.ru/img/users/{$item->id}_original.{$item->photo_extension}", 'r')
                );
            } catch (\Exception $e) {
                $photo->delete();
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
