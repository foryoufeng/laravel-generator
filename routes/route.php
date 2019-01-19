<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Foryoufeng\Generator')->group(function () {
    $route = ltrim(config('generator.route', 'generator'), '/').'/';

    Route::get($route, 'GeneratorController@index')->name('generator.index');
    Route::get($route.'model/{name}', 'GeneratorController@dummyValues')->name('generator.dummyValues');
    Route::post($route, 'GeneratorController@store');
    Route::get($route.'template', 'GeneratorTemplateController@index')->name('generator.template.index');
    Route::get($route.'template/update', 'GeneratorTemplateController@update')->name('generator.template.update');
    Route::post($route.'template/save', 'GeneratorTemplateController@save')->name('generator.template.save');
    Route::post($route.'template/delete', 'GeneratorTemplateController@delete')->name('generator.template.delete');
});
