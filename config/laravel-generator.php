<?php

return [
    'name' => 'Laravel Generator',
    // the url to access
    'route'=>'laravel-generator',
    // the rule  can be used by the field
    'rules'=>[
        'string',
        'email',
        'file',
        'numeric',
        'array',
        'alpha',
        'alpha_dash',
        'alpha_num',
        'date',
        'boolean',
        'distinct',
        'phone',
        'custom'
    ],
    'tags'=>[
        [
            'name'=>'Controller',
            'path'=>'app/Http/Controllers/Admin/',
            'file'=>'DummyClassController.php',
            'type'=>'primary',
        ],
        [
            'name'=>'Test',
            'path'=>'tests/Unit',
            'file'=>'DummyClassTest.php',
            'type'=>'danger',
        ],
        [
            'name'=>'Vue',
            'path'=>'resources/views/admin/DummySnakeClass/',
            'file'=>'index.vue',
            'type'=>'warning',
        ],
        [
            'name'=>'Request',
            'path'=>'app/Http/Requests/',
            'file'=>'DummyClassRequest.php',
            'type'=>'success',
        ]
    ],
    'custom_keys'=>[
        'author'=>env('GENERATOR_AUTHOR','system')
    ]
];
