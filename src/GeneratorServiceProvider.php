<?php

namespace Foryoufeng\Generator;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Foryoufeng\Generator\Console\InstallCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        // Publishing the views.
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-generator');

        //the language
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-generator');

        // Publishing generator files.
        $this->publishes([
            __DIR__.'/../config/generator.php' => config_path('generator.php'),
            __DIR__.'/../resources/assets' => public_path('vendor/laravel-generator'),
            __DIR__.'/../resources/migrations' => database_path('migrations'),
        ]);

        //routes
        $this->loadRoutesFrom(__DIR__.'/../routes/route.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * Register any package services.
     */
    public function register()
    {
    }
}
