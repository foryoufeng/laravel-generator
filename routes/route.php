<?php

use Foryoufeng\Generator\Controllers\GeneratorController;
use Foryoufeng\Generator\Controllers\AssetController;
use Foryoufeng\Generator\Controllers\GeneratorTemplateController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('laravel-generator.route'))->group(function () {
    Route::get('{locale?}', [GeneratorController::class, 'index'])->name('generator.index')->where('locale', 'en|zh_CN');
    Route::get('model/{name?}', [GeneratorController::class, 'dummyValues'])->name('generator.dummyValues');
    Route::get('table/{table_name?}', [GeneratorController::class, 'createByTable'])->name('generator.create_by_table');
    Route::post('/', [GeneratorController::class, 'store'])->name('generator.store');
    Route::post('migrate', [GeneratorController::class, 'migrate'])->name('generator.migrate');
    Route::get('template', [GeneratorTemplateController::class, 'index'])->name('generator.template.index');
    Route::get('template/update/{locale?}', [GeneratorTemplateController::class, 'update'])->name('generator.template.update');
    Route::post('template/save', [GeneratorTemplateController::class, 'save'])->name('generator.template.save');
    Route::post('template/delete', [GeneratorTemplateController::class, 'delete'])->name('generator.template.delete');
    Route::post('template/compile', [GeneratorTemplateController::class, 'compile'])->name('generator.template.compile');
    Route::post('template/updateType', [GeneratorTemplateController::class, 'updateType'])->name('generator.template.updateType');
    Route::get('logs', [GeneratorController::class, 'getLogs'])->name('generator.logs');
    Route::post('log/delete', [GeneratorController::class, 'deleteLog'])->name('generator.deleteLog');
    Route::get('assets/{path}', AssetController::class)->where('path', '.*');
});

