<?php

/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 12/27/18
 * Time: 10:30 AM.
 */

namespace Foryoufeng\Generator;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Class GeneratorUtils.
 */
class GeneratorUtils
{
    /**
     * @param $template
     * @return string
     * @throws \Exception
     */
    public static function compile($template) :string
    {
        $template = str_replace('<?php','#php#',$template);
        // 提供的演示数据
        $laravel_generators = static::getGenerators();
        // 可用的假属性字段
        $dummyAttrs = static::getDummyAttrs();
        $replacements = [];
        foreach ($dummyAttrs as $key => $placeholder) {
            if (isset($laravel_generators[$key])) {
                $replacements[$placeholder] = $laravel_generators[$key];
            }
        }
        $data = array_merge($laravel_generators,[
            'customKeys' => static::getCustomKeys()
        ]);
        try {
            $result = Blade::render($template,$data);
            $replacements['#php#'] = '<?php';
            return str_replace(array_keys($replacements), array_values($replacements), $result);
        }catch (\Exception $exception){
            Log::error($exception);
            throw new \Exception($exception->getMessage()." in line-".$exception->getLine());
        }
    }
    /**
     * get all of tables in the default database.
     */
    public static function getTables(): array
    {
        $info = [];
        $driver = DB::getDriverName();
        $database = DB::getConfig('database');
        $prefix = DB::getConfig('prefix');

        $tableNames = match ($driver) {
            'sqlite' => array_map(
                fn ($row) => $row->name,
                DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
            ),

            'mysql' => array_map(
                fn ($row) => $row->{"Tables_in_$database"},
                DB::select('SHOW TABLES')
            ),

            'pgsql' => array_map(
                fn ($row) => $row->tablename,
                DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'")
            ),

            default => throw new \RuntimeException("Unsupported DB driver: $driver"),
        };

        foreach ($tableNames as $table) {
            $cleanName = $prefix ? str_replace($prefix, '', $table) : $table;
            $info[] = [
                'name' => $cleanName,
                'columns' => Schema::getColumnListing($table),
            ];
        }

        return $info;
    }

    /**
     * get general Engines.
     *
     * @return array
     */
    public static function getGeneralEngines()
    {
        $engines = [];
        $dbEngines = ['InnoDB', 'MyISAM', 'MEMORY', 'ARCHIVE'];
        foreach ($dbEngines as $k => $engine) {
            $engines[$k]['label'] = $engine;
            $engines[$k]['value'] = $engine;
        }

        return $engines;
    }

    /**
     * get dbTypes.
     *
     * @return array
     */
    public static function getDbTypes()
    {
        $dbTypes = [];
        $types = [
            'string', 'integer', 'text', 'float', 'double', 'decimal', 'boolean', 'date', 'time',
            'dateTime', 'timestamp', 'char', 'mediumText', 'longText', 'tinyInteger', 'smallInteger',
            'mediumInteger', 'bigInteger', 'unsignedTinyInteger', 'unsignedSmallInteger', 'unsignedMediumInteger',
            'unsignedInteger', 'unsignedBigInteger', 'enum', 'json', 'jsonb', 'dateTimeTz', 'timeTz',
            'timestampTz', 'nullableTimestamps', 'binary', 'ipAddress', 'macAddress',
        ];
        foreach ($types as $k => $type) {
            $dbTypes[$k]['label'] = $type;
            $dbTypes[$k]['value'] = $type;
        }

        return $dbTypes;
    }

    /**
     * 获取可用的假字段.
     *
     * @return array
     */
    public static function getDummyAttrs()
    {
        return [
            'className' => 'DummyClass',
            'classDisplayName' => 'DummyDisplayName',
            'camelClassName' => 'DummyCamelClass',
            'snakeClassName' => 'DummySnakeClass',
            'pluralClassName' => 'DummyPluralClass',
            'snakePluralClassName' => 'DummySnakePluralClass',
        ];
    }
    public static function getCustomKeys()
    {
        return config('laravel-generator.custom_keys', []);
    }

    public static function getTags():array
    {
        return [
            [
                'name'=>'Controller',
                'path'=>'app/Http/Controllers/Admin/',
                'file'=>'DummyClassController.php',
                'type'=>'primary',
            ],
            [
                'name'=>'Test',
                'path'=>'tests/Unit',
                'file'=>'DummyClassTest.php',
                'type'=>'danger',
            ],
            [
                'name'=>'Vue',
                'path'=>'resources/views/admin/DummySnakeClass/',
                'file'=>'index.vue',
                'type'=>'warning',
            ],
            [
                'name'=>'Request',
                'path'=>'app/Http/Requests/',
                'file'=>'DummyClassRequest.php',
                'type'=>'success',
            ]
        ];
    }

    /**
     * 根据名称获取转换的字段的值
     *
     * @param  $modelName  名称
     * @return array
     */
    public static function getDummyValues($modelName)
    {
        return [
            'DummyClass' => $modelName,
            'DummyCamelClass' => Str::camel($modelName),
            'DummySnakeClass' => Str::snake($modelName),
            'DummyPluralClass' => Str::plural($modelName),
            'DummySnakePluralClass' => Str::snake(Str::plural($modelName)),
        ];
    }

    /**
     * 获取函数数据.
     *
     * @return array
     */
    public static function getFunctions()
    {
        return [
            // the if
            'if' => '@if(true)

@else

@endif',
            // the elseif
            'elseif' => '@if(true)

@elseif

@else

@endif',
            // the tableFieldsFor
            'for' => '@foreach($tableFields as $field)
{{ $field[\'field_name\'] }}
@endforeach',

            // the tableFieldsFor
            'soft_deletes' => '@if($modelFields[\'soft_deletes\'])

@endif
',
            'timestamps' => '@if($modelFields[\'timestamps\'])

@endif
',
            'primary_key' => '{{ $modelFields[\'primary_key\']}}',
            // the tableFieldsFor
            'fillable' => 'protected \$fillable = [@foreach($tableFields as $field) @if($field[\'field_name\']!=\'id\')\'{{ $field[\'field_name\'] }}\',@endif @endforeach];',

            // the rule
            'rule' => '@foreach ($tableFields as $field)
    @if(\'file\'==$field[\'rule\'])
    <input type=\'file\' name=\'{{$field[\'field_name\'] }}\'>
    @endif
@endforeach',
            'relationships' => "<%for(relationship of DummyRelationShips){%>
    <%if('hasMany'==relationship.relationship) { %>
     public function <%=relationship.snake_plural_model%>(){
         return \$this->hasMany(<%=relationship.model%>::class <%if(relationship.foreign_key) { %>,'<%=relationship.foreign_key%>'<%}%>);
     }
    <%}else{%>
     public function <%=relationship.snake_model%>(){
         return \$this-><%=relationship.relationship%>(<%=relationship.model%>::class <%if(relationship.foreign_key) { %>,'<%=relationship.foreign_key%>'<%}%>);
     }
    <%}%>
<%}%>",
        ];
    }

    /**
     * @return array
     */
    public static function getGenerators()
    {
        return [
            'className' => 'LaravelGenerator',
            'classDisplayName' => 'my laravel generator',
            'camelClassName' => 'laravelGenerator',
            'snakeClassName' => 'laravel_generator',
            'pluralClassName' => 'LaravelGenerators',
            'snakePluralClassName' => 'laravel_generators',
            'tableFields' => [
                [
                    'field_name' => 'user_id',
                    'field_display_name' => 'User Id',
                    'type' => 'integer',
                    'attach' => '',
                    'nullable' => false,
                    'key' => '',
                    'is_show_lists' => true,
                    'can_search' => true,
                    'rule' => 'numeric',
                ],
                [
                    'field_name' => 'name',
                    'field_display_name' => trans('laravel-generator::generator.name'),
                    'type' => 'string',
                    'attach' => '255',
                    'nullable' => false,
                    'key' => 'unique',
                    'is_show_lists' => true,
                    'can_search' => true,
                    'rule' => 'string',
                ],
                [
                    'field_name' => 'add_time',
                    'field_display_name' => trans('laravel-generator::generator.addTime'),
                    'type' => 'timestamp',
                    'nullable' => true,
                    'key' => '',
                    'is_show_lists' => true,
                    'can_search' => true,
                    'rule' => 'date',
                ],
                [
                    'field_name' => 'upload_file',
                    'field_display_name' => trans('laravel-generator::generator.file'),
                    'type' => 'string',
                    'nullable' => true,
                    'key' => '',
                    'is_show_lists' => true,
                    'can_search' => false,
                    'rule' => 'file',
                ],
            ],
            'modelFields' => [
                'primary_key' => 'id',
                'timestamps' => true,
                'soft_deletes' => true,
            ],
            'relationships' => [
                [
                    'relationship' => 'belongsTo',
                    'model' => 'LaravelGeneratorType',
                    'camel_model' => 'laravelGeneratorType',
                    'snake_model' => 'laravel_generator_type',
                    'snake_plural_model' => 'laravel_generator_types',
                    'foreign_key' => 'template_id',
                    'reverse' => 'hasMany',
                    'with' => true,
                    'can_search' => true,
                ],
            ],
        ];
    }
}
