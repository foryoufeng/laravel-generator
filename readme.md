# laravel-generator

## [中文文档](readme_zh_CN.md)

<p align="center">⛵<code>laravel-generator</code> is administrative interface builder for laravel which can help you build code template you want as soon as possiable.</p>

Requirements
------------
 - PHP >= 7.0.0
 - Laravel >= 5
 
 # For GUI
<img src="https://cdn.linkgoup.com/laravel_generator_v2_en_index.png" alt="laravel-generator">

## [More Docs](https://doc.linkgoup.com/docs/show/669)

## Installation

Via Composer

``` bash
composer require --dev foryoufeng/laravel-generator
```

If you do not run Laravel 5.5 (or higher), then add the service provider in `config/app.php`:
```
Foryoufeng\Generator\GeneratorServiceProvider::class
```

Then run the command to install the generator
```
php artisan generator:install
```

After run command you can find config file in `config/generator.php`,and now you can access your application `http://yourhost/generator` to use the `laravel-generator`

After the installation is complete, the project generates templates such as model, controllers and views by default. Other templates can be added or modified according to the actual needs of the project.

## templates

<img src="https://cdn.linkgoup.com/laravel_generator_en_template.png" alt="laravel-generator">
 
## Usage
the `generator.php` doc
```
<?php

return [
    'name' => 'Laravel-generator',
    //the url to access
    'route'=>'generator',//you can change the access url if you do not like it
     //the rule  can be used by the field  , You can define the format of the fields you need.
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
      ],
];
```

## stub
You can define the format of the stub you need followed by my rules,such as

<img src="https://cdn.linkgoup.com/laravel_generator_v2_en.png" alt="laravel-generator">

## Notice

Only Chinese and English are supported by laravel generator now 

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email foryoufeng@gmail.com instead of using the issue tracker.

## License

MIT. Please see the [license file](license.md) for more information.