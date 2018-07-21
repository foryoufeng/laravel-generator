# laravel-generator

<p align="center">⛵<code>laravel-generator</code> 是一个为laravel应用快速生成代码的图形化界面代码生成器</p>

要求
------------
 - PHP >= 7.0.0
 - Laravel >= 5.5.0
 
 # UI界面
<img src="https://cdn.linkgoup.com/laravel-generator.png" alt="laravel-admin">

## 安装

通过Composer

``` bash
composer require --dev foryoufeng/laravel-generator
```

运行如下命令来发布资源文件

```
php artisan vendor:publish --provider="Foryoufeng\Generator\GeneratorServiceProvider"
```
运行后，你能在`config/generator.php`中配置你的代码生成数据,代码模板默认在`resources/generators` 目录下, 
现在你可以访问你的应用url`http://yourhost/generator` 来使用`laravel-generator`了

## 用法
`generator.php` 文件说明
```
<?php

return [
    'name' => 'Laravel-generator',//应用的名称
    //访问的地址
    'route'=>'generator',//如果你不想访问这个地址，可以换掉
    'modelPath'=>'App\\Models\\', //模型所在的命名空间
    'views'=>[],  //待实现
    'multiple'=>[  // 多文件生成
        // group file
        [
            'name'=>'controllers',  // 在界面的label中显示的名称
            //文件的后缀
            'postfix'=>'Controller', //如UserController,后缀是Controller ,或者 UserTest 后缀是Test
            'group'=>[
                [
                    'namespace'=>'App\\Http\\Controllers\\',//所在的空间
                    'stub'=>resource_path('generators').'/controllers/home_controller.stub',  //模板所在位置
                    'isChecked'=>true //页面上是否选中
                ],
                [
                    'namespace'=>'App\\Http\\Controllers\\Api\\',
                    'stub'=>resource_path('generators').'/controllers/api_controller.stub',
                    'isChecked'=>true
                ]
            ],
        ]
    ],
    'single'=>[  // 单个文件生成
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

## 模板
```
<?php

namespace DummyNamespace; //会被替换为你在配置文件中定义的`namespace` 

use DummyModelNamespace; //模型的路径 如App\Models\User
use Prettus\Repository\Eloquent\BaseRepository;
//变成模型名拼接后缀`model_name`.`postfix`  如`UserController`
class DummyClass extends BaseRepository  
{

    /**
     * @return string
     */
    public function model()
    {
        //`DummyModelUcfirst` 对应模型名，如 `User`
        return DummyModelUcfirst::class;
    }
}
```
## 更新记录

查看 [changelog](changelog.md) 获取更新记录


MIT. Please see the [license file](license.md) for more information.