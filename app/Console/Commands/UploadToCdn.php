<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UploadToCdn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:cdn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload JS and CSS to CDN (DO Spaces)';

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
        \Storage::disk('spaces')->put('lk2/js/app.js', file_get_contents(public_path() . '/js/app.js'));
        \Storage::disk('spaces')->put('lk2/css/app.css', file_get_contents(public_path() . '/css/app.css'));
    }
}
