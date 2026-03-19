<?php

namespace ProfessionalChacha\PhpQueryOptimizer\Laravel;

use Illuminate\Support\ServiceProvider;
use ProfessionalChacha\PhpQueryOptimizer\Laravel\Commands\AnalyzeQueriesCommand;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AnalyzeQueriesCommand::class,
            ]);
        }
    }
}
