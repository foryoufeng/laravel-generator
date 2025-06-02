<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-01
 * Time: 18:19.
 */

namespace Foryoufeng\Generator\Controllers;

use Doctrine\DBAL\Exception;
use Foryoufeng\Generator\FileCreator;
use Foryoufeng\Generator\GeneratorUtils;
use Foryoufeng\Generator\Message;
use Foryoufeng\Generator\MigrationCreator;
use Foryoufeng\Generator\Models\LaravelGenerator;
use Foryoufeng\Generator\Models\LaravelGeneratorLog;
use Foryoufeng\Generator\Models\LaravelGeneratorType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class GeneratorController extends BaseController
{
    use Message;

    /**
     * access IU.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ?string $locale = null)
    {
        $locale = $locale ?? config('app.locale', 'en');
        if (! in_array($locale, ['en', 'zh_CN'])) {
            $locale = 'en';
        }
        App::setLocale($locale);
        $generator = config('laravel-generator');
        // 设置展示的tab
        $tab = $request->get('tab', 'log');
        // 获取所有的表
        $tables = GeneratorUtils::getTables();
        // 获取可用的数据类型
        $dbTypes = GeneratorUtils::getDbTypes();
        // 获取模板列表
        $template_types = $this->getTemplateTypes();
        // 获取模型的信息
        $modelInfo = $this->getModelInfo();
        // 获取可用的规则
        $rules = $this->getRules();
        // 可用的假属性字段
        $dummyAttrs = GeneratorUtils::getDummyAttrs();
        // 自定义变量
        $customDummys = config('laravel-generator.customDummys');
        $language_value = $locale === 'en' ? 'English' : '简体中文';

        return view('laravel-generator::index', compact('dbTypes', 'generator', 'language_value', 'locale',
            'tab', 'template_types', 'dummyAttrs', 'tables', 'rules', 'modelInfo', 'customDummys'));
    }

    /**
     * 获取指定的名称的转换数据.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dummyValues($name)
    {
        if ($name) {
            return $this->success(GeneratorUtils::getDummyValues($name));
        }

        return $this->error(trans('generator.error'));
    }

    /**
     * save data.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $paths = [];
        $data = $request->validate([
            'id' => 'required',
            'modelName' => 'required',
            'primary_key' => 'required',
            'soft_deletes' => 'required',
            'timestamps' => 'required',
            'modelDisplayName' => 'required',
            'submit_type' => 'required',
            'relationships' => 'array',
            'table_fields' => 'array',
            'generator_templates' => 'array',
        ]);
        $model_name = $data['modelName'];
        $log = LaravelGeneratorLog::firstOrNew([
            'model_name' => $model_name,
        ]);
        $item['model_name'] = $model_name;
        $item['display_name'] = $data['modelDisplayName'];
        $item['creator'] = config('laravel-generator.customDummys.DummyAuthor', '');
        $item['configs'] = json_encode($request->except('id'));
        $log->fill($item);
        $res = $log->save();
        if ($data['submit_type'] === 'save') {
            if ($res) {
                return $this->success(['save success']);
            }

            return $this->error('save error');
        }
        // 获取模型的信息
        $modelInfo = $this->getModelInfo();
        try {
            $table_fields = $request->get('table_fields');
            // 生成数据
            $model_name = $data['modelName'];

            $create = $request->get('create', []);
            // 1. 是否运行 Create migration.
            if (\in_array('migration', $create, true)) {
                $table_name = Str::plural(Str::snake(class_basename($model_name)));
                $migrationName = 'create_'.$table_name.'_table';

                $paths['migration'] = (new MigrationCreator(app('files'), database_path('migrations')))->buildBluePrint(
                    $table_fields,
                    'id',
                    $request->get('timestamps'),
                    $request->get('soft_deletes'),
                    $request->get('foreigns')
                )->create($migrationName, database_path('migrations'), $table_name);
            }
            // 2. 是否运行Run migrate.
            if (\in_array('migrate', $create, true)) {
                Artisan::call('migrate');
                $message = Artisan::output();
                $paths['migrate'] = $message;
            }
            // 4.生成模板文件
            $generator_templates = $data['generator_templates'];
            $file = app('files');

            foreach ($generator_templates as $k => $template) {
                $file_real_name = $template['file_real_name'];
                $content = GeneratorUtils::compile($template['template'],$data);
                $path = base_path($file_real_name);
                if ($file->exists($path)) {
                    // route special handling
                    if (str_contains($file_real_name, 'routes/') && str_contains($file_real_name, '.php')) {
                        $file->append($path, str_replace('<?php', '', $content));
                        $paths['files-'.($k + 1)] = "file [$file_real_name] append success !";
                    } else {
                        $paths['files-'.($k + 1)] = "file [$file_real_name] already exists!";
                    }
                } else {
                    $paths['files-'.($k + 1)] = (new FileCreator($template['file_real_name'], $content))->create();
                }
            }
            // 5.处理关联关系
            $relationships = $request->get('relationships');
            $this->dealRelationShips($relationships, $model_name, $modelInfo);
            // 6.是否运行idea代码提示
            if (\in_array('ide-helper', $create, true)) {
                Artisan::call('ide-helper:models', [
                    '--write' => true,
                    '--write-eloquent-helper' => true,
                    'model' => [
                        ucfirst(str_replace('/', '\\', $modelInfo->path).$model_name),
                    ],
                ]);
            }
        } catch (\Exception $exception) {
            return $this->error($exception->getFile().'-'.$exception->getLine().':'.$exception->getMessage());
        }

        return $this->success($paths);
    }

    public function migrate(Request $request)
    {
        $doMigrate = $request->get('doMigrate', []);
        $table_fields = $request->get('table_fields');
        try {
            // 新增加迁移文件
            if (\in_array('migration', $doMigrate, true)) {
                $tableName = $request->get('tableName');
                $migrationName = $request->get('prefix').'_';
                if (count($table_fields) > 2) {
                    $migrationName .= $table_fields[0]['field_name'].'AndMore';
                } else {
                    $migrationName .= collect($table_fields)->pluck('field_name')->implode('_');
                }
                $migrationName .= '_'.$tableName.'_table';
                $paths['migration'] = (new MigrationCreator(app('files'), database_path('migrations')))->buildBluePrint($table_fields, null, false)
                    ->create($migrationName, database_path('migrations'), $tableName, false);
                //  Run migrate.
                if (\in_array('migrate', $request->get('doMigrate'), true)) {
                    Artisan::call('migrate');
                    $message = Artisan::output();
                    $paths['migrate'] = $message;
                }
            }
         } catch (\Exception $exception) {
             return $this->error($exception->getFile().'-'.$exception->getLine().':'.$exception->getMessage());
        }

        return $this->success($paths);
    }
    private function dealRelationShips($relationships, $model_name, $modelInfo)
    {
        if ($relationships) {
            foreach ($relationships as $relationship) {
                // 替换相对模型的数据
                $file_name = base_path($modelInfo->path).$relationship['model'].'.php';
                if ($relationship['reverse'] && file_exists($file_name)) {
                    $oldData = file_get_contents($file_name);
                    $oldData = str_replace(["\n\n\n", "\n    \n"], ["\n\n", ''], substr($oldData, 0, -1));
                    $oldData = substr($oldData, 0, -1);
                    if ($relationship['reverse'] === 'hasMany') {
                        $funName = Str::snake(Str::plural($model_name));
                    } else {
                        $funName = Str::camel($model_name);
                    }
                    $key = '';
                    if ($relationship['foreign_key']) {
                        $key = ",'{$relationship['foreign_key']}'";
                    }
                    $oldData .= "     public function {$funName}(){\n";
                    $oldData .= "         return \$this->{$relationship['reverse']}({$model_name}::class{$key});\n";
                    $oldData .= "     }\n\n}";
                    file_put_contents($file_name, $oldData);
                }
            }
        }

        return true;
    }

    /**
     * 获取可用的规则.
     *
     * @return array
     */
    private function getRules()
    {
        $rules = [];
        $configRules = config('laravel-generator.rules');
        foreach ($configRules as $k => $rule) {
            $rules[$k]['label'] = $rule;
            $rules[$k]['value'] = $rule;
        }

        return $rules;
    }

    /**
     * 获取模型的信息.
     *
     * @return mixed
     */
    private function getModelInfo()
    {
        $model = LaravelGenerator::whereHas('template_type', function ($query) {
            $query->whereName(LaravelGeneratorType::MODEL);
        })->first();
        if (! $model) {
            throw new \RuntimeException('the template model not found');
        }

        return $model;
    }

    /**
     * get the all template types.
     *
     * @return LaravelGeneratorType[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    private function getTemplateTypes()
    {
        $data = LaravelGeneratorType::with('templates')->get();
        $select = $data->map(function ($item) {
            $data = [];
            $data['label'] = $item->name;
            $data['value'] = $item->id;

            return $data;
        });
        $data = $data->map(function ($item) {
            $item->checked = $item->templates->filter(function ($value) {
                return $value['is_checked'];
            })->pluck('id');
            $item->templates = $item->templates->map(function ($temp) {
                $temp->file_real_name = $temp->path.$temp->file_name;

                return $temp;
            });

            return $item;
        });

        return [
            'datas' => $data,
            'select' => $select,
        ];
    }

    public function getLogs(Request $request)
    {
        $model_name = $request->get('model_name');
        $display_name = $request->get('display_name');
        $creator = $request->get('creator');

        $datas = LaravelGeneratorLog::when($model_name, fn ($query) => $query->where('model_name', 'like', '%'.$model_name.'%'))
            ->when($display_name, fn ($query) => $query->where('display_name', 'like', '%'.$display_name.'%'))
            ->when($creator, fn ($query) => $query->where('creator', 'like', '%'.$creator.'%'))
            ->orderBy('id', 'desc')
            ->paginate();

        return $this->success($datas);
    }

    public function deleteLog(Request $request)
    {
        $id = $request->get('id');

        $res = LaravelGeneratorLog::whereId($id)->delete();

        if ($res) {
            return $this->success('success');
        }

        return $this->error('delete error');
    }

    public function createByTable(string $table_name)
    {
        if (!$table_name) {
            return $this->error('table_name is required');
        }
        try {
            $table_columns = GeneratorUtils::tableToForm($table_name);

            return $this->success($table_columns);

        }catch (Exception $exception){
            return $this->error($exception->getMessage());
        }
    }
}
