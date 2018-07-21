<?php

namespace Foryoufeng\Generator;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
            // Publishing the configuration file.
            $this->publishes([
                __DIR__.'/../config/generator.php' => config_path('generator.php'),
            ]);
            // Publishing the views.
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-generator');

            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/laravel-generator')]);
            // Publishing generators.
            $this->publishes([
                __DIR__.'/../resources/generators' => resource_path('generators'),
            ]);
            $this->registerRoute();
    }

    /**
     * register route
     * can change in config/generator.php ->route
     */
    protected function registerRoute(){
        $route=config('generator.route','generator');
        Route::get($route,'Foryoufeng\Generator\GeneratorController@index');
        Route::post($route,'Foryoufeng\Generator\GeneratorController@store');
    }
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
       // $this->mergeConfigFrom(__DIR__.'/../config/generator.php', 'generator');

        // Register the service the package provides.
//        $this->app->singleton('generator', function ($app) {
//            return new Generator;
//        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['generator'];
    }
}