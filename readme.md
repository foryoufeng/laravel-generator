# laravel-generator

## [中文文档](readme_zh_CN.md)

<p align="center">⛵<code>laravel-generator</code> is administrative interface builder for laravel which can help you build code template you want as soon as possiable.</p>

Requirements
------------
 - PHP >= 7.0.0
 - Laravel >= 5.5.0
 
 # For GUI
<img src="https://cdn.linkgoup.com/laravel-generator.png" alt="laravel-admin">

## Installation

Via Composer

``` bash
composer require --dev foryoufeng/laravel-generator
```

Then run the command to publish resources and config：

```
php artisan vendor:publish --provider="Foryoufeng\Generator\GeneratorServiceProvider"
```
After run command you can find config file in `config/generator.php`,and you can configure the position of the stub  and the file you want to generate, the default stub will be in `resources/generators` directory, in this file you can config your file.
now you can access your application `http://yourhost/generator` to use the `laravel-generator`

## Usage
the `generator.php` doc
```
<?php

return [
    'name' => 'Laravel-generator',
    //the url to access
    'route'=>'generator',//you can change the access url if you do not like it
    'modelPath'=>'App\\Models\\', //where your models in
    'views'=>[],  //waiting to finish
    'multiple'=>[  // to genetate multiple file 
        // group file
        [
            'name'=>'controllers',  // which is the label name in the html
            //The file suffix
            'postfix'=>'Controller', //such as UserController,the postfix is Controller ,or UserTest the postfix is Test
            'group'=>[
                [
                    'namespace'=>'App\\Http\\Controllers\\',
                    'stub'=>resource_path('generators').'/controllers/home_controller.stub',  //where the stub in
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
    'single'=>[  // to genetate one file 
        [
            'name'=>'dao',
            'namespace'=>'App\\Http\\Daos\\',
            'stub'=>resource_path('generators').'/dao.stub',
            'isChecked'=>true,
            'postfix'=>'Dao'
        ]
    ],
];
```

## stub
```
<?php

namespace DummyNamespace; //which is the `namespace` you defined in the config

use DummyModelNamespace; //is the model such as App\Models\User
use Prettus\Repository\Eloquent\BaseRepository;
//is the `model_name`.`postfix` such as `UserController`
class DummyClass extends BaseRepository  
{

    /**
     * @return string
     */
    public function model()
    {
        //`DummyModelUcfirst` is mean to the model_name ,such as `User`
        return DummyModelUcfirst::class;
    }
}
```
## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email foryoufeng@gmail.com instead of using the issue tracker.

## License

MIT. Please see the [license file](license.md) for more information.