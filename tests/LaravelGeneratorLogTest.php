<?php

use App\Models\LaravelGeneratorLog;
use Illuminate\Support\Facades\Artisan;

test('add data', function () {
   LaravelGeneratorLog::factory()->count(5)->create();
   expect(LaravelGeneratorLog::count())->toBeGreaterThan(0);
});
