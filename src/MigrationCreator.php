<?php

namespace Foryoufeng\Generator;

use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;

class MigrationCreator extends BaseMigrationCreator
{
    /**
     * @var string
     */
    protected $bluePrint = '';

    protected $isCreate=true;
    /**
     * Create a new model.
     *
     * @param string    $name
     * @param string    $path
     * @param null      $table
     * @param bool|true $create
     *
     * @return string
     */
    public function create($name, $path, $table = null, $create = true)
    {
        $this->ensureMigrationDoesntAlreadyExist($name);

        $path = $this->getPath($name, $path);
        $stub = $this->files->get(resource_path('/generators/create.stub'));

        $this->isCreate=$create;
        $this->files->put($path, $this->populateStub($name, $stub, $table));

        $this->firePostCreateHooks();

        return $path;
    }

    /**
     * Populate stub.
     *
     * @param string $name
     * @param string $stub
     * @param string $table
     *
     * @return mixed
     */
    protected function populateStub($name, $stub, $table)
    {
        $type=$this->isCreate?'create':'table';
        return str_replace(
            ['DummyClass', 'DummyTable', 'DummyStructure','create'],
            [$this->getClassName($name), $table, $this->bluePrint,$type],
            $stub
        );
    }

    /**
     * Build the table blueprint.
     *
     * @param array      $fields
     * @param string     $keyName
     * @param bool|true  $useTimestamps
     * @param bool|false $softDeletes
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function buildBluePrint($fields = [], $keyName = 'id', $useTimestamps = true, $softDeletes = false)
    {
        $fields = array_filter($fields, function ($field) {
            return isset($field['field_name']) && !empty($field['field_name']) ;
        });

        if (empty($fields)) {
            throw new \Exception('Table fields can\'t be empty');
        }

        if(isset($keyName)){
            $rows[] = "\$table->increments('$keyName');\n";
        }
        foreach ($fields as $k=>$field) {
            $column = "\$table->{$field['type']}('{$field['field_name']}')";

            if ($field['key']) {
                $column .= "->{$field['key']}()";
            }

            if (isset($field['default']) && $field['default']) {
                $column .= "->default('{$field['default']}')";
            }

            if (isset($field['comment']) && $field['comment']) {
                $column .= "->comment('{$field['comment']}')";
            }

            if (array_get($field, 'nullable')) {
                $column .= '->nullable()';
            }
            if (isset($field['change']) && $field['change']) {
                $column .= "->change()";
            }
            $rows[] = $column.";\n";
        }

        if ($useTimestamps) {
            $rows[] = "\$table->timestamps();\n";
        }

        if ($softDeletes) {
            $rows[] = "\$table->softDeletes();\n";
        }

        $this->bluePrint = trim(implode(str_repeat(' ', 12), $rows), "\n");

        return $this;
    }
}
