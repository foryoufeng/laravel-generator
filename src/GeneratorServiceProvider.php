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

        // load migrations
        $this->loadMigrationsFrom(__DIR__.'/../resources/migrations');

        // Publishing generator files.
        $this->publishes([
            __DIR__.'/../config/laravel-generator.php' => config_path('laravel-generator.php'),
        ],'laravel-generator');

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
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-generator.php', 'laravel-generator'
        );
    }
}
