<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-07-01
 * Time: 18:19
 */

namespace Foryoufeng\Generator;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class GeneratorController extends BaseController
{
    public function index()
    {
        $dbTypes = $this->getDbTypes();
        $generator=config('generator');
        $multiple=$generator['multiple'];
        //deal multiple config
        foreach ($multiple as $k=>$item){
             $all_checkLists[$k]['name']='all_'.$item['name'];
             $all_checkLists[$k]['value']=collect($item['group'])->pluck('namespace');
             $checkLists[$k]['name']=$item['name'];
             $checkLists[$k]['postfix']=$item['postfix'];
             $checkLists[$k]['value']=collect($item['group'])->filter(function($value){
                 return $value['isChecked'];
             })->pluck('namespace');
        }
        return view('laravel-generator::index',compact('dbTypes',
            'generator','all_checkLists','checkLists'));
     }

    /**
     * get dbTypes
     * @return array
     */
    private function getDbTypes()
    {
        $dbTypes=[];
        $types = [
            'string', 'integer', 'text', 'float', 'double', 'decimal', 'boolean', 'date', 'time',
            'dateTime', 'timestamp', 'char', 'mediumText', 'longText', 'tinyInteger', 'smallInteger',
            'mediumInteger', 'bigInteger', 'unsignedTinyInteger', 'unsignedSmallInteger', 'unsignedMediumInteger',
            'unsignedInteger', 'unsignedBigInteger', 'enum', 'json', 'jsonb', 'dateTimeTz', 'timeTz',
            'timestampTz', 'nullableTimestamps', 'binary', 'ipAddress', 'macAddress',
        ];
        foreach ($types as $k=>$type){
            $dbTypes[$k]['label']=$type;
            $dbTypes[$k]['value']=$type;
        }
        return $dbTypes;
     }
    public function store(Request $request)
    {
        $paths = [];
        try {

            $model_name=$request->get('modelName');
            $modelName = config('generator.modelPath') . $model_name;
            $table_fields=$request->get('table_fields');
            // 1. Create model.
            if (in_array('model', $request->get('create',[]))) {
                $modelCreator = new ModelCreator($modelName);

                $paths['model'] = $modelCreator->create(
                    $request->get('primary_key'),
                    $request->get('timestamps'),
                    $request->get('soft_deletes')
                );
            }
            //2.Create multiple
            $multiple=config('generator.multiple');
            foreach ($multiple as $item ){
                $files=$request->get($item['name'],[]);
                $paths[$item['name']]='';
                foreach ($files as $file){
                    foreach ($item['group'] as $v){
                        if($v['namespace']==$file){
                            $fileName=$v['namespace'].$model_name.$item['postfix'];
                            $stub=$v['stub'];
                            $paths[$item['name']].='<br/>'.(new SingleCreator($fileName,$stub))->create($modelName);
                        }
                    }
                }
            }
            //3.Create single file
            if(!empty($request->get('single'))){
                $single=$request->get('single');
                foreach ($single as $item){
                    if($item['isChecked']){
                        $fileName=$item['namespace'].$model_name.$item['postfix'];
                        $stub=$item['stub'];
                        $paths[$item['name']]=(new SingleCreator($fileName,$stub))->create($modelName);
                    }
                }
            }
            // 4. Create migration.
            if (in_array('migration', $request->get('create',[]))) {
                $table_name = Str::plural(Str::snake(class_basename($model_name)));
                $migrationName = 'create_'.$table_name.'_table';

                $paths['migration'] = (new MigrationCreator(app('files')))->buildBluePrint(
                    $table_fields,
                    $request->get('primary_key', 'id'),
                    $request->get('timestamps'),
                    $request->get('soft_deletes')
                )->create($migrationName, database_path('migrations'), $table_name);
            }
            // 5. Run migrate.
            if (in_array('migrate', $request->get('create',[]))) {
                Artisan::call('migrate');
                $message = Artisan::output();
                $paths['migrate']=$message;
            }
            if (in_array('ide-helper', $request->get('create',[]))) {
                Artisan::call('ide-helper:models',['--write'=>true]);
                $message = Artisan::output();
                $paths['ide-helper:model']=$message;
            }
            //6.Create unit test.
            if($request->get('unittest')){
                Artisan::call('make:test',['name'=>$model_name.'Test','--unit'=>'unit']);
                $message = Artisan::output();
                $paths['unit_test']=$message;
            }

            //7. add migrate
            if(in_array('migration',$request->get('doMigrate',[]))){
                $tableName=$request->get('tableName');
                $migrationName=$request->get('prefix').'_';
                if(count($table_fields)>2){
                    $migrationName.=$table_fields[0]['field_name'].'AndMore';
                }else{
                    $migrationName.=collect($table_fields)->pluck('field_name')->implode('_');
                }
                $migrationName.='_'.$tableName.'_table';
                $paths['migration'] = (new MigrationCreator(app('files')))->buildBluePrint($table_fields,null,false)
                    ->create($migrationName, database_path('migrations'), $tableName,false);
                //  Run migrate.
                if (in_array('migrate', $request->get('doMigrate'))) {
                    Artisan::call('migrate');
                    $message = Artisan::output();
                    $paths['migrate']=$message;
                }
            }
        } catch (\Exception $exception) {

            // Delete generated files if exception thrown.
           // app('files')->delete($paths);

            return response()->json(['message'=>$exception->getFile().'-'.$exception->getLine().':'.$exception->getMessage(),'code'=>0,'data'=>[]]);
        }
        return response()->json(['message'=>'success','code'=>200,'data'=>$paths]);
     }
}