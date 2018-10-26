<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\Task;
use App\Observers\LogsObserver;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();

        // Bind logs watcher to all models
        $path = realpath(app_path() . '/Models');
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
            if ($filename->isFile()) {
                $class = mb_strimwidth($filename->getRealPath(), strlen($path) + 1, -4);
                $class = str_replace("/", "\\", $class);
                $class = getModelClass($class);
                if (! defined($class . '::DISABLE_LOGS')) {
                    $class::observe(LogsObserver::class);
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
