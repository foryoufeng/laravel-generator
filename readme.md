<p align="center">
<a href="https://generator.pp-lang.tech"><img src="https://generator.pp-lang.tech/laravel-generator-logo2.png" width="400" alt="Laravel Generator"></a>
</p>

<p align="center">
<a href="https://packagist.org/packages/foryoufeng/laravel-generator"><img src="https://img.shields.io/packagist/dt/foryoufeng/laravel-generator" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/foryoufeng/laravel-generator"><img src="https://img.shields.io/packagist/v/foryoufeng/laravel-generator" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/foryoufeng/laravel-generator"><img src="https://img.shields.io/packagist/l/foryoufeng/laravel-generator" alt="License"></a>
</p>

# Laravel Generator
## [中文文档](readme_zh_CN.md)
A graphical interface code generator for quickly generating code for Laravel applications.


# Installation

If you have PHP and Composer installed, you can install the Laravel installer via Composer:

```bash
composer require --dev foryoufeng/laravel-generator
```

Run the following command to install the code generator:

```
php artisan generator:install
```

Add the creator's information in the `.env` file:
```sh
GENERATOR_AUTHOR=Your Name
```

Now you can access your application URL `http://localhost:8000/laravel-generator` to use `laravel-generator`.

## Configuration file

Publish configuration file

```sh
php artisan vendor:publish --tag=laravel-generator
```

`generator.php` file description:

```php
<?php
return [
    'name' => 'Laravel Generator',
    // the url to access
    'route'=>'laravel-generator',
    // Define rules
    'rules' => [
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
    // Custom parameters
    'custom_keys'=>[
        'author'=>env('GENERATOR_AUTHOR','system')
    ]
];
```

## Update Log

View [changelog](changelog.md) for update logs.

MIT. Please see the [license file](license.md) for more information.
