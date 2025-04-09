<?php

return [
    'name' => 'Laravel-generator',
    //the url to access
    'route'=>'generator',
    //the rule  can be used by the field
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
    //difine your custom value
    'customDummys'=>[
        'DummyAuthor'=>env('DUMMY_AUTHOR','foryoufeng')
    ]
];
