<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-01
 * Time: 18:19.
 */

namespace Foryoufeng\Generator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Foryoufeng\Generator\Models\LaravelGenerator;
use Illuminate\Routing\Controller as BaseController;
use Foryoufeng\Generator\Models\LaravelGeneratorType;

class GeneratorController extends BaseController
{
    use Message;

    /**
     * access IU.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $generator = config('generator');
        //设置展示的tab
        $tab = $request->get('tab');
        //获取所有的表
        $tables = GeneratorUtils::getTables();
        //获取可用的数据类型
        $dbTypes = GeneratorUtils::getDbTypes();
        //获取模板列表
        $template_types = $this->getTemplateTypes();
        //获取模型的信息
        $modelInfo = $this->getModelInfo();
        //获取可用的规则
        $rules = $this->getRules();
        //可用的假属性字段
        $dummyAttrs = GeneratorUtils::getDummyAttrs();
        //自定义变量
        $customDummys=config('generator.customDummys');

        return view('laravel-generator::index', compact('dbTypes', 'generator',
            'tab', 'template_types', 'dummyAttrs', 'tables', 'rules', 'modelInfo','customDummys'));
    }

    /**
     * 获取指定的名称的转换数据.
     *
     * @param $name
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
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $paths = [];
        //获取模型的信息
        $modelInfo = $this->getModelInfo();
        try {
            $doMigrate = $request->get('doMigrate', []);
            $table_fields = $request->get('table_fields');
            //生成数据
            if (!$doMigrate) {
                $data = $request->validate([
                    'modelName' => 'required',
                    'generator_templates' => 'array',
                ]);
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
                //4.生成模板文件
                $generator_templates = $data['generator_templates'];
                $file = app('files');

                foreach ($generator_templates as $k => $template) {
                    $file_real_name = $template['file_real_name'];
                    $path = base_path($file_real_name);
                    if ($file->exists($path)) {
                        // route special handling
                        if(str_contains($file_real_name,'routes/') && str_contains($file_real_name,'.php')){
                            $file->append($path, str_replace('<?php','',$template['template']));
                            $paths['files-'.($k+1)] = "file [$file_real_name] append success !";
                        }else{
                            $paths['files-'.($k+1)] = "file [$file_real_name] already exists!";
                        }
                    }else{
                        $paths['files-'.($k+1)] = (new FileCreator($template['file_real_name'], $template['template']))->create();
                    }
                }
                //5.处理关联关系
                $relationships=$request->get('relationships');
                $this->dealRelationShips($relationships,$model_name,$modelInfo);
                //6.是否运行idea代码提示
                if (\in_array('ide-helper', $create, true)) {
                        Artisan::call('ide-helper:models', [
                            '--write' => true,
                            '--write-eloquent-helper' => true,
                            'model'=>[
                                ucfirst(str_replace('/','\\',$modelInfo->path).$model_name)
                            ]
                        ]);
                }
            }

            //新增加迁移文件
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
                if (\in_array('migrate', $request->get('doMigrate'),true)) {
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

    private function dealRelationShips($relationships,$model_name,$modelInfo)
    {
        if($relationships){
            foreach ($relationships as $relationship){
                //替换相对模型的数据
                $file_name=base_path($modelInfo->path).$relationship['model'].'.php';
                if($relationship['reverse'] && file_exists($file_name)){
                    $oldData=file_get_contents($file_name);
                    $oldData=str_replace(["\n\n\n", "\n    \n"], ["\n\n", ''], substr($oldData,0,-1));
                    $oldData=substr($oldData,0,-1);
                    if('hasMany'==$relationship['reverse']){
                        $funName=Str::snake(Str::plural($model_name));
                    }else{
                        $funName=Str::camel($model_name);
                    }
                    $key='';
                    if($relationship['foreign_key']){
                        $key=",'{$relationship['foreign_key']}'";
                    }
                    $oldData.="     public function {$funName}(){\n";
                    $oldData.="         return \$this->{$relationship['reverse']}({$model_name}::class{$key});\n";
                    $oldData.="     }\n\n}";
                    file_put_contents($file_name,$oldData);
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
        $configRules = config('generator.rules');
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
        if (!$model) {
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
        $datas = LaravelGeneratorType::with('templates')->get();
        $select = $datas->map(function ($item) {
            $data = [];
            $data['label'] = $item->name;
            $data['value'] = $item->id;

            return $data;
        });
        $datas = $datas->map(function ($item) {
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
            'datas' => $datas,
            'select' => $select,
        ];
    }
}
