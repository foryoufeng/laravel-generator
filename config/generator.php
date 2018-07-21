<?php

return [
    'name' => 'Laravel-generator',
    //the url to access
    'route'=>'generator',
    'modelPath'=>'App\\Models\\',
    'views'=>[],
    'multiple'=>[
        // group file
        [
            'name'=>'controllers',
            //The file suffix
            'postfix'=>'Controller',
            'group'=>[
                [
                    'namespace'=>'App\\Http\\Controllers\\',
                    'stub'=>resource_path('generators').'/controllers/home_controller.stub',
                    'isChecked'=>true //you can change it to false
                ],
                [
                    'namespace'=>'App\\Http\\Controllers\\Api\\',
                    'stub'=>resource_path('generators').'/controllers/api_controller.stub',
                    'isChecked'=>true
                ]
            ],
        ]
    ],
    //one file
    'single'=>[
        [
            'name'=>'dao',
            'namespace'=>'App\\Http\\Daos\\',
            'stub'=>resource_path('generators').'/dao.stub',
            'isChecked'=>true,
            'postfix'=>'Dao'
        ]
    ],
];