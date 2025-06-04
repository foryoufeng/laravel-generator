<?php

use Foryoufeng\Generator\GeneratorUtils;

test('get doctrine table', function () {
    $table = 'laravel_generators';
    $table_info = GeneratorUtils::getDoctrineTable($table);
    $res = $table_info->getPrimaryKey();
    dump($res);
    expect($res)->not->toBeEmpty();
});
test('get table columns', function () {
    $table = 'laravel_generators';
    $columns = GeneratorUtils::getTableColumns($table);
    dump($columns);
    expect($columns)->not->toBeEmpty();
});

test('table to form', function () {
    $table = 'laravel_generators';
    $columns = GeneratorUtils::tableToForm($table);
    expect($columns)->not->toBeEmpty();
});
