<?php

/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 12/27/18
 * Time: 10:30 AM.
 */

namespace Foryoufeng\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Class GeneratorUtils.
 */
class GeneratorUtils
{
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
            'currentTime' => 'DummyCurrentTime',
            'pluralClassName' => 'DummyPluralClass',
            'snakePluralClassName' => 'DummySnakePluralClass',
            'tableFields' => 'DummyTableFields',
            'relationships' => 'DummyRelationShips',
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
            'DummyCurrentTime' => date('Y-m-d H:i:s'),
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
            'if' => '<%if(false) { %>

<%}else{%>

<h2>is else</h2>

<%}%>',
            // the elseif
            'elseif' => '<%if(false) { %>

<%}else if(1==1){%>

else if
<%}else{%>

<h2>is else</h2>

<%}%>',
            // the for
            'for' => '<%for(var i=0;i<10;i++){%>
<li><%=i%></li>

<%}%>',
            // the tableFields
            'tableFields' => '<tr>
<%for(field of DummyTableFields){%>
    <%if(field.is_show_lists) { %>
    <td><%=field.field_display_name%></td>
    <%}%>
<%}%>
</tr>
@foreach ($datas as $data)
<tr>
<%for(field of DummyTableFields){%>
    <%if(field.is_show_lists) { %>
    <td>{{ $data-><%=field.field_name%> }}</td>
    <%}%>
<%}%>
</tr>
<tr>
@endforeach
',
            // the tableFieldsFor
            'tableFieldsFor' => '<%for(item of DummyTableFields){%>
<%=item.field_name%>
<%}%>
',
            // the tableFieldsFor
            'primary_key' => "<%if('id'!=DummyModelFields.primary_key){%>
protected \$primaryKey = '<%=DummyModelFields.primary_key%>';
<%}%>
",
            // the tableFieldsFor
            'timestamps' => '<%if(!DummyModelFields.timestamps){%>
public $timestamps = false;
<%}%>
',
            // the tableFieldsFor
            'soft_deletes' => '<%if(!DummyModelFields.soft_deletes){%>

<%}%>
',
            // the tableFieldsFor
            'fillable' => 'protected $fillable = [<%for(item of DummyTableFields){%><%if(\'id\'!=item.field_name) { %>\'<%=item.field_name%>\',<%}%><%}%>];',

            // the var
            'var' => '<%=Template%>',
            // the rule
            'rule' => "<%for(field of DummyTableFields){%>
    <%if('file'==field.rule) { %>
    <input type='file' name='<%=field.field_name%>'>
    <%}%>
<%}%>",
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
            'currentTime' => date('Y-m-d H:i:s'),
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
                'soft_deletes' => false,
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
