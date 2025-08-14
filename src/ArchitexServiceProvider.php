<?php

namespace LaravelArchitex;

use Illuminate\Support\ServiceProvider;
use LaravelArchitex\Console\Commands\MakeRepositoryCommand;
use LaravelArchitex\Console\Commands\MakeServiceCommand;
use LaravelArchitex\Console\Commands\MakeEventCommand;
use LaravelArchitex\Console\Commands\MakeCommandCommand;
use LaravelArchitex\Console\Commands\MakeQueryCommand;
use LaravelArchitex\Console\Commands\MakeDDDCommand;
use LaravelArchitex\Console\Commands\MakeCQRSCommand;
use LaravelArchitex\Services\ArchitectureGenerator;
use LaravelArchitex\Services\TemplateEngine;

class ArchitexServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/architex.php', 'architex'
        );

        $this->app->singleton(ArchitectureGenerator::class);
        $this->app->singleton(TemplateEngine::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepositoryCommand::class,
                MakeServiceCommand::class,
                MakeEventCommand::class,
                MakeCommandCommand::class,
                MakeQueryCommand::class,
                MakeDDDCommand::class,
                MakeCQRSCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/architex.php' => config_path('architex.php'),
                __DIR__.'/../stubs' => base_path('stubs/architex'),
            ], 'architex-config');
        }
    }
} 