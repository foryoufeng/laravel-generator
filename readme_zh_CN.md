<p align="center">
<a href="https://generator.pp-lang.tech"><img src="https://generator.pp-lang.tech/laravel-generator-logo2.png" width="400" alt="Laravel Generator"></a>
</p>

<p align="center">
<a href="https://packagist.org/packages/foryoufeng/laravel-generator"><img src="https://img.shields.io/packagist/dt/foryoufeng/laravel-generator" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/foryoufeng/laravel-generator"><img src="https://img.shields.io/packagist/v/foryoufeng/laravel-generator" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/foryoufeng/laravel-generator"><img src="https://img.shields.io/packagist/l/foryoufeng/laravel-generator" alt="License"></a>
</p>

# Laravel Generator
为laravel应用快速生成代码的图形化界面代码生成器


## 安装

通过Composer

``` bash
composer require --dev foryoufeng/laravel-generator
```

运行如下命令来安装代码生成器

```
php artisan generator:install
```

在`.env`中添加配置创建人的信息
```sh
GENERATOR_AUTHOR=你的名字
```

现在您可以访问您的应用url`http://localhost:8000/laravel-generator` 来使用`Laravel Generator`了


## 配置文件

发布配置文件

```sh
php artisan vendor:publish --tag=laravel-generator
```

`generator.php` 文件说明

```
<?php

return [
    'name' => 'Laravel Generator',
    // 访问地址
    'route'=>'laravel-generator',
    // 定义规则
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
    //自定义参数
    'custom_keys'=>[
        'author'=>env('GENERATOR_AUTHOR','system')
    ]
];
```

## 更新记录

查看 [changelog](changelog.md) 获取更新记录

MIT. Please see the [license file](license.md) for more information.
