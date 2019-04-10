<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\{Report\Report, Payment\Payment};
use App\Observers\{LogsObserver, ReportsObserver, PaymentsObserver};
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Carbon;

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

        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('Y-m-d H:i:s');
        });

        Report::observe(ReportsObserver::class);
        Payment::observe(PaymentsObserver::class);

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
