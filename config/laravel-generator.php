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
    'custom_keys'=>[
        'author'=>env('GENERATOR_AUTHOR','system')
    ]
];
