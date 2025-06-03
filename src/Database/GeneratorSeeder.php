<?php

/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 1/14/19
 * Time: 10:53 AM.
 */

namespace Foryoufeng\Generator\Database;

use Foryoufeng\Generator\GeneratorUtils;
use Foryoufeng\Generator\Models\LaravelGenerator;
use Foryoufeng\Generator\Models\LaravelGeneratorLog;
use Foryoufeng\Generator\Models\LaravelGeneratorType;
use Illuminate\Database\Seeder;

class GeneratorSeeder extends Seeder
{
    public function run()
    {
        // 添加模型
        $this->addModel();
        // 添加控制器
        $this->addControllers();
        // 添加视图
        $this->addViews();
        // 添加路由
        $this->addRoute();
        // add logs
        $this->addLogs();
    }

    private function addLogs()
    {
        $count = LaravelGeneratorLog::count();
        if ($count === 0) {
            $generators = GeneratorUtils::getGenerators();
            LaravelGeneratorLog::create([
                'model_name' => 'User',
                'display_name' => 'User List',
                'creator' => 'system',
                'configs' => json_encode([
                    'modelName' => 'User',
                    'modelDisplayName' => 'User List',
                    'foreigns' => [],
                    'relationships' => [],
                    'templates' => [],
                    'create' => [
                        'migration', 'migrate', 'ide-helper',
                    ],
                    'primary_key' => 'id',
                    'timestamps' => true,
                    'soft_deletes' => false,
                    'table_fields' => $generators['tableFields'],
                ]),
            ]);
        }
    }

    private function addRoute()
    {
        $type = LaravelGeneratorType::firstOrCreate([
            'name' => LaravelGeneratorType::Route,
        ]);
        $generator = LaravelGenerator::firstOrNew([
            'name' => 'route',
        ]);
        if (! $generator->exists) {
            $generator->path = 'routes/';
            $generator->file_name = 'admin.php';
            $generator->is_checked = 1;
            $generator->template = $this->getRouteTemplate();
            $generator->template_id = $type->id;
            $generator->save();
        }
    }

    private function getRouteTemplate()
    {
        return <<<'stub'
<?php
Route::get('DummySnakeClass',[DummyClassController::class,'index'])->name('admin.DummySnakeClass.index');
Route::post('DummySnakeClass/update',[DummyClassController::class,'update'])->name('admin.DummySnakeClass.update');
Route::post('DummySnakeClass/delete',[DummyClassController::class,'delete'])->name('admin.DummySnakeClass.delete');
stub;

    }

    /**
     * add Model.
     */
    private function addModel()
    {
        $type = LaravelGeneratorType::firstOrCreate([
            'name' => LaravelGeneratorType::MODEL,
        ]);
        $generator = LaravelGenerator::firstOrNew([
            'name' => 'model',
        ]);
        if (! $generator->exists) {
            $generator->path = 'app/Models';
            $generator->file_name = 'DummyClass.php';
            $generator->is_checked = 1;
            $generator->template = $this->getModelTemplate();
            $generator->template_id = $type->id;
            $generator->save();
        }
    }

    /**
     * add Controllers.
     */
    private function addControllers()
    {
        $type = LaravelGeneratorType::firstOrCreate([
            'name' => LaravelGeneratorType::Controllers,
        ]);
        $generator = LaravelGenerator::firstOrNew([
            'name' => 'Admin Controller',
        ]);
        $controllerTemps = $this->getControllersTemplate();
        if (! $generator->exists) {
            $generator->path = 'app/Http/Controllers/Admin/';
            $generator->file_name = 'DummyClassController.php';
            $generator->is_checked = 1;
            $generator->template = $controllerTemps['admin'];
            $generator->template_id = $type->id;
            $generator->save();
        }
    }

    /**
     * add Views.
     */
    private function addViews()
    {
        $type = LaravelGeneratorType::firstOrCreate([
            'name' => LaravelGeneratorType::Views,
        ]);

        $generator = LaravelGenerator::firstOrNew([
            'name' => 'index_view',
        ]);
        $viewTemp = $this->getViewsTemplate();
        if (! $generator->exists) {
            $generator->path = 'resources/views/admin/DummySnakeClass/';
            $generator->file_name = 'index.vue';
            $generator->is_checked = 1;
            $generator->template = $viewTemp['index'];
            $generator->template_id = $type->id;
            $generator->save();
        }
        $generator = LaravelGenerator::firstOrNew([
            'name' => 'update_view',
        ]);
        if (! $generator->exists) {
            $generator->path = 'resources/views/admin/DummySnakeClass/';
            $generator->file_name = 'update.vue';
            $generator->is_checked = 1;
            $generator->template = $viewTemp['update'];
            $generator->template_id = $type->id;
            $generator->save();
        }
    }

    /**
     * @return array
     */
    private function getControllersTemplate()
    {
        $homeTemp = <<<stub
<?php
/**
 *
 * DummyDisplayName
 * author: {{\$customKeys['author']}}
 * created_at: {{ date('Y-m-d H:i:s') }}
 */
namespace App\Http\Controllers\Admin;

use App\Models\DummyClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class DummyClassController extends Controller
{

    public function index(Request \$request): JsonResponse
    {
           \$create_start_time = \$request->get('create_start_time');
           \$create_end_time = \$request->get('create_end_time');
@foreach(\$tableFields as \$field)
@if(\$field['can_search'])
           \${{\$field['field_name'] }} = \$request->get('{{\$field['field_name'] }}');
@endif
@endforeach
            \$data = DummyClass::orderByDesc('id')
@foreach(\$tableFields as \$field)
@if(\$field['can_search'])
@if('numeric'==\$field['rule'])
                    ->when(\${{\$field['field_name'] }}, fn (Builder \$query) => \$query->where('{{\$field['field_name'] }}',  \${{\$field['field_name'] }}))
@elseif('string'==\$field['rule'])
                    ->when(\${{\$field['field_name'] }}, fn (Builder \$query) => \$query->where('{{\$field['field_name'] }}', 'like', "%\${{\$field['field_name'] }}%"))
@else
                    ->when(\${{\$field['field_name'] }}, fn (Builder \$query) => \$query->where('{{\$field['field_name'] }}', 'like', "%\${{\$field['field_name'] }}%"))
@endif
@endif
@endforeach
                    ->when(\$create_start_time, fn (Builder \$query) => \$query->where('created_at', '>=', \$create_start_time))
                    ->when(\$create_end_time, fn (Builder \$query) => \$query->where('created_at', '<=', \$create_end_time))
                    ->paginate();

            \$data->getCollection()->transform(function (DummyClass \$DummySnakeClass){
                //\$DummySnakeClass->setAttribute('id', 'ID');

                return \$DummySnakeClass;
            });

            return response()->json(['message' => 'success', 'errcode' => 0, 'data' => \$data->toArray()]);
    }

    public function update(Request \$request)
    {
        \$id = (int)\$request->get('id');
        \$DummySnakeClass = null;
        if(\$id){
            \$DummySnakeClass = DummyClass::whereId(\$id)->first();
        }
        \$data=\$request->validate([
            'id' => 'required|int',
@foreach(\$tableFields as \$field)
@if('string'==\$field['rule'] && false==\$field['nullable'])
            '{{\$field['field_name'] }}' => 'required'
@endif
@endforeach
        ],[],[
            'id' => 'ID',
@foreach(\$tableFields as \$field)
@if('string'==\$field['rule'] && false==\$field['nullable'])
            '{{\$field['field_name'] }}' => '{{\$field['field_display_name'] }}'
@endif
@endforeach
        ]);

        if(!\$DummySnakeClass){
            \$DummySnakeClass=new DummyClass();
        }
        \$DummySnakeClass->fill(\$data);
        if(\$DummySnakeClass->save()){
            return response()->json(['message' => '保存成功', 'errcode' => 0, 'data' => []]);
        }
        return response()->json(['message' => '保存失败', 'errcode' => 1, 'data' => []]);
    }

    public function delete(Request \$request)
    {
        \$id = (int)\$request->get('id');
        \$DummySnakeClass = DummyClass::whereId(\$id)->first();
        if(\$DummySnakeClass && \$DummySnakeClass->delete()){
            return response()->json(['message' => '删除成功', 'errcode' => 0, 'data' => []]);
        }
        return response()->json(['message' => '删除失败', 'errcode' => 1, 'data' => []]);
    }
}
stub;

        return [
            'admin' => $homeTemp,
        ];
    }

    /**
     * @return array
     */
    private function getViewsTemplate()
    {
        $index_temp = <<<'stub'
<template>
        <el-form ref="form" :model="form" label-width="60px">
            <el-row>
@foreach($tableFields as $field)
@if($field['can_search'] && 'string'==$field['rule'])
                    <el-col :span="4">
                        <el-form-item label="{{$field['field_display_name'] }}">
                            <el-input v-model="form.{{$field['field_name'] }}" @keyup.enter.native="getData()"></el-input>
                        </el-form-item>
                    </el-col>
@endif
@endforeach
                <el-col :span="4">
                    <el-form-item>
                        <el-button type="primary" @click="getData()">查询</el-button>
                        <a href="/DummySnakeClass/update" target="_blank"><el-button type="danger">添加</el-button></a>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
    <el-main>
        <el-table
                :data="tableData"
                stripe
                border
                v-loading="loading"
                style="width: 100%;">
            <el-table-column
                    prop="id"
                    label="ID"
                    width="180">
            </el-table-column>
@foreach($tableFields as $field)
@if($field['is_list_display'])
                    <el-table-column
                        prop="{{$field['field_name'] }}"
                        label="{{$field['field_display_name'] }}"
                        width="180">
                    </el-table-column>
@endif
@endforeach
            <el-table-column
                    fixed="right"
                    label="操作"
                    width="200">
                <template slot-scope="scope">
                    <a :href="'/DummySnakeClass/update'+scope.row.id" target="_blank">
                        <el-button type="primary" icon="el-icon-edit" circle></el-button>
                    </a>
                    <el-button @click="handelDelete(scope.row.id)" type="danger" icon="el-icon-delete" circle></el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-main>
    <el-footer>
        <div v-if="pageInfo.total > pageInfo.per_page" class="text-center">
            <el-pagination
                    @current-change="handlePage"
                    :current-page="pageInfo.current_page"
                    :page-sizes="[pageInfo.per_page]"
                    :page-size="pageInfo.per_page"
                    layout="total,sizes, prev, pager, next, jumper"
                    :total="pageInfo.total">
            </el-pagination>
        </div>
    </el-footer>
</template>
<script>
import { ref, reactive, onMounted } from 'vue'

onMounted( () => {

})
</script>
<style scoped lang="scss">
</style>
stub;
        $update_temp = <<<'stub'
<template>
    <div class="box-header">
        <el-header  id="content-header">
            <el-breadcrumb separator-class="el-icon-arrow-right">
                <el-breadcrumb-item><a href="/DummySnakeClass">DummyDisplayName</a></el-breadcrumb-item>
                <el-breadcrumb-item>@{{ form.id?'编辑':'添加' }}</el-breadcrumb-item>
            </el-breadcrumb>
        </el-header>
        <el-main>
            <el-form ref="form" :model="form" :rules="rules" label-width="80px">
@foreach($tableFields as $field)
@if('string' == $field['rule'])
                    <el-form-item label="{{$field['field_display_name'] }}" prop="{{$field['field_name'] }}">
                        <el-input v-model="form.{{$field['field_name'] }}"></el-input>
                    </el-form-item>
@endif
@endforeach
                <el-form-item>
                    <el-button type="primary" @click="onSubmit('form')" :loading="submitLoading">确定</el-button>
                </el-form-item>
            </el-form>
        </el-main>
    </div>
</template>>
<script>
import { ref, reactive, onMounted } from 'vue'

onMounted( () => {

})
</script>
<style scoped lang="scss">
</style>
stub;

        return [
            'index' => $index_temp,
            'update' => $update_temp,
        ];
    }

    /**
     * get the model template.
     *
     * @return string
     */
    private function getModelTemplate()
    {
        return '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
@if($modelFields[\'soft_deletes\'])
use Illuminate\Database\Eloquent\SoftDeletes;
@endif


class DummyClass extends Model
{

    /** @@use HasFactory<\Database\Factories\DummyClassFactory> */
    use HasFactory;
@if($modelFields[\'soft_deletes\'])
    use SoftDeletes;
@endif
@if(!$modelFields[\'timestamps\'])
    public $timestamps = false;
@endif

@foreach($relationShips as $relationship)
@if(\'hasMany\'==$relationship[\'relationship\'])
     public function {{$relationship[\'snake_plural_model\']}}(){
         return $this->hasMany({{$relationship[\'model\']}}::class @if($relationship[\'foreign_key\']),\'{{$relationship[\'foreign_key\']}}\'@endif);
     }
@else
     public function {{$relationship[\'snake_model\']}}(){
         return $this->{{$relationship[\'relationship\']}}({{$relationship[\'model\']}}::class @if($relationship[\'foreign_key\']),\'{{$relationship[\'foreign_key\']}}\'@endif);
     }
@endif
@endforeach
}';
    }
}
