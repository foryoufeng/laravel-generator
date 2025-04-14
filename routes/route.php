<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Foryoufeng\Generator')->group(function () {
    Route::get('generator/{locale?}', 'GeneratorController@index')->name('generator.index') ->where('locale', 'en|zh-CN');
    Route::get('generator/model/{name?}', 'GeneratorController@dummyValues')->name('generator.dummyValues');
    Route::post('generator', 'GeneratorController@store')->name('generator.store');
    Route::get('generator/template', 'GeneratorTemplateController@index')->name('generator.template.index');
    Route::get('generator/template/update/{locale?}', 'GeneratorTemplateController@update')->name('generator.template.update');
    Route::post('generator/template/save', 'GeneratorTemplateController@save')->name('generator.template.save');
    Route::post('generator/template/delete', 'GeneratorTemplateController@delete')->name('generator.template.delete');
    Route::post('generator/template/updateType', 'GeneratorTemplateController@updateType')->name('generator.template.updateType');
    Route::get('generator/logs', 'GeneratorController@getLogs')->name('generator.logs');
    Route::post('generator/log/delete', 'GeneratorController@deleteLog')->name('generator.deleteLog');
});
