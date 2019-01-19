<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-09
 * Time: 22:38.
 */

namespace Foryoufeng\Generator;

class FileCreator
{
    /**
     * file.
     *
     * @var string
     */
    protected $file_real_name;

    /**
     * file content.
     *
     * @var
     */
    protected $template;
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * FileCreator constructor.
     *
     * @param $file_real_name
     * @param $template
     */
    public function __construct($file_real_name, $template)
    {
        $this->file_real_name = $file_real_name;
        $this->template = $template;
        $this->files = app('files');
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function create()
    {
        $path = base_path($this->file_real_name);

        if ($this->files->exists($path)) {
            throw new \Exception("file [$this->file_real_name] already exists!");
        }

        if (!$this->files->isDirectory(dirname($path))) {
            try {
                $this->files->makeDirectory(dirname($path), 0755, true);
            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage().$path);
            }
        }
        $this->files->put($path, $this->template);

        return $path;
    }
}
