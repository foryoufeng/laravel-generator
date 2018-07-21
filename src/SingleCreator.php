<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-09
 * Time: 22:38
 */

namespace Foryoufeng\Generator;

use Illuminate\Support\Str;

class SingleCreator
{
    /**
     * Model name.
     *
     * @var string
     */
    protected $name;

    /**
     * stub path
     * @var
     */
    protected $stub;
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * ModelCreator constructor.
     *
     * @param string $tableName
     * @param string $name
     * @param null   $files
     */
    public function __construct($name,$stub, $files = null)
    {
        $this->name = $name;

        $this->stub=$stub;

        $this->files = $files ?: app('files');
    }

    /**
     * Create a new migration file.
     *
     * @param string     $keyName
     * @param bool|true  $timestamps
     * @param bool|false $softDeletes
     *
     * @throws \Exception
     *
     * @return string
     */
    public function create($model_name)
    {
        $path = $this->getpath($this->name);

        if ($this->files->exists($path)) {
            throw new \Exception("file [$this->name] already exists!");
        }

        //get the stub file
        $stub = $this->files->get($this->stub);

        $stub = $this->replaceClass($stub, $this->name)
            ->populateModelClass($stub,$model_name)
            ->replaceNamespace($stub, $this->name)
            ->replaceSpace($stub);

        if(!$this->files->isDirectory(dirname($path))){
            try{
                $this->files->makeDirectory(dirname($path));
            }catch (\Exception $exception){
                throw new \Exception($exception->getMessage());
            }
        }
        $this->files->put($path, $stub);

        return $path;
    }

    /**
     * Get path for migration file.
     *
     * @param string $name
     *
     * @return string
     */
    public function getPath($name)
    {
        $segments = explode('\\', $name);

        array_shift($segments);

        return app_path(implode('/', $segments)).'.php';
    }

    /**
     * Replace spaces.
     *
     * @param string $stub
     *
     * @return mixed
     */
    public function replaceSpace($stub)
    {
        return str_replace(["\n\n\n", "\n    \n"], ["\n\n", ''], $stub);
    }
    /**
     * Get namespace of giving class full name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace class dummy.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceClass(&$stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $stub = str_replace('DummyClass', $class, $stub);

        return $this;
    }

    /**
     * Replace model class dummy.
     *
     * @param string $stub
     * @param string $model_name
     *
     * @return $this
     */
    protected function populateModelClass(&$stub, $model_name)
    {
        $class = str_replace($this->getNamespace($model_name).'\\', '', $model_name);

        $stub = str_replace(['DummyModelNamespace','DummyModelUcfirst','DummyModelLower'], [$model_name,$class,Str::lower($class)], $stub);

        return $this;
    }

    /**
     * Replace namespace dummy.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);

        return $this;
    }
}
