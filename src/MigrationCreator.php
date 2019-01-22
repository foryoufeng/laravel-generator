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

    protected $fields;
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
        $stub = $this->get_stub();

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
        if($this->isCreate){
            //删除表
            $down="Schema::dropIfExists('{$table}');";
        }else{
            //删除修改表的字段
            $down="Schema::table('{$table}', function (Blueprint \$table) {\n";
            foreach ($this->fields as $field){
                if(!$field['change']){
                    $down.="            \$table->dropColumn('{$field['field_name']}');\n";
                }
            }
            $down.='        });';
        }
        return str_replace(
            ['DummyClass', 'DummyTable', 'DummyStructure','create','DummyDownTable'],
            [$this->getClassName($name), $table, $this->bluePrint,$type,$down],
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
    public function buildBluePrint($fields = [], $keyName = 'id', $useTimestamps = true, $softDeletes = false,$foreigns=[])
    {
        $fields = array_filter($fields, function ($field) {
            return isset($field['field_name']) && !empty($field['field_name']) ;
        });

        if (empty($fields)) {
            throw new \Exception('Table fields can\'t be empty');
        }

        //设置字段
        $this->fields=$fields;

        if(isset($keyName)){
            $rows[] = "\$table->increments('$keyName');\n";
        }
        foreach ($fields as $k=>$field) {

            if(isset($field['attach'])){
                $column = "\$table->{$field['type']}('{$field['field_name']}',{$field['attach']})";
            }else{
                $column = "\$table->{$field['type']}('{$field['field_name']}')";
            }

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

        //添加关联关系
        if($foreigns){
            $rows[] = "\n";
            foreach ($foreigns as $foreign){
                $onDelete='';
                if(isset($foreign['onDelete']) && $foreign['onDelete']){
                    $onDelete="->onDelete('{$foreign['onDelete']}')";
                }
                $onUpdate='';
                if(isset($foreign['onUpdate']) && $foreign['onUpdate']){
                    $onUpdate="->onUpdate('{$foreign['onUpdate']}')";
                }
                $rows[] = "\$table->foreign('{$foreign['foreign']}')->references('{$foreign['references']}')->on('{$foreign['on']}'){$onDelete}{$onUpdate};\n";
            }
        }

        $this->bluePrint = trim(implode(str_repeat(' ', 12), $rows), "\n");

        return $this;
    }

    private function get_stub()
    {
        return <<<stub
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DummyClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DummyTable', function (Blueprint \$table) {
            DummyStructure
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DummyDownTable
    }
}

stub;

    }
}
