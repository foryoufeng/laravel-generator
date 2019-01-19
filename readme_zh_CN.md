# laravel-generator

<p align="center">⛵<code>laravel-generator</code> 是一个为laravel应用快速生成代码的图形化界面代码生成器</p>

要求
------------
 - PHP >= 7.0.0
 - Laravel >= 5
 
 # UI界面
<img src="https://cdn.linkgoup.com/laravel_generator_zh_index.png" alt="laravel-generator">

## [更多文档](https://doc.linkgoup.com/docs/show/669)

## 安装

通过Composer

``` bash
composer require --dev foryoufeng/laravel-generator
```

如果你是运行的Laravel 5.5以下的版本，需要在`config/app.php`的service provider中添加：

```
Foryoufeng\Generator\GeneratorServiceProvider::class
```

运行如下命令来安装代码生成器

```
php artisan generator:install
```

运行后，你能在`config/generator.php`中配置你的代码

现在你可以访问你的应用url`http://yourhost/generator` 来使用`laravel-generator`了

安装完成后，项目默认生成了model,controllers和views这几个模板，其他模板可以根据自己项目的实际需要进行添加或者修改

## 模板

<img src="https://cdn.linkgoup.com/laravel_generator_zh_template.png" alt="laravel-generator">
  
## 用法
`generator.php` 文件说明
```
<?php

return [
    'name' => 'Laravel-generator',//应用的名称
    //访问的地址
    'route'=>'generator',//如果你不想访问这个地址，可以换掉
    //字段的规则 , 你也可以定义你自己的验证规则
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

## 模板
你可以根据项目给出的模板格式来定义你说需要的模板，例如

<img src="https://cdn.linkgoup.com/laravel_generator_v2_zh.png" alt="laravel-generator">


## 注意

项目现在只支持中文和英文2种语言

## 更新记录

查看 [changelog](changelog.md) 获取更新记录


MIT. Please see the [license file](license.md) for more information.