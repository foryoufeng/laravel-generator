<?php
/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 1/14/19
 * Time: 10:53 AM.
 */

namespace Foryoufeng\Generator\Database;

use Foryoufeng\Generator\GeneratorUtils;
use Foryoufeng\Generator\Models\LaravelGeneratorLog;
use Illuminate\Database\Seeder;
use Foryoufeng\Generator\Models\LaravelGenerator;
use Foryoufeng\Generator\Models\LaravelGeneratorType;

class GeneratorSeeder extends Seeder
{
    public function run()
    {
        //添加模型
        $this->addModel();
        //添加控制器
        $this->addControllers();
        //添加视图
        $this->addViews();
        //添加路由
        $this->addRoute();
        // add logs
        $this->addLogs();
    }

    private function addLogs()
    {
        $count = LaravelGeneratorLog::count();
        if($count === 0){
            $generators = GeneratorUtils::getGenerators();
            LaravelGeneratorLog::create([
                'model_name'=>'User',
                'display_name' =>'User List',
                'creator' =>'system',
                'configs' =>json_encode([
                    'modelName'=>'User',
                    'modelDisplayName'=>'User List',
                    'foreigns'=>[],
                    'create' => [
                        'migration',"migrate","ide-helper"
                    ],
                    'primary_key' => 'id',
                    'timestamps' => true,
                    'soft_deletes' => false,
                    'tableFields' => $generators['tableFields'],
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
        if (!$generator->exists) {
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
        return <<<stub
<?php
Route::get('DummySnakeClass','DummyClassController@index')->name('admin.DummySnakeClass.index');
Route::post('DummySnakeClass/update','DummyClassController@update')->name('admin.DummySnakeClass.update');
Route::post('DummySnakeClass/delete','DummyClassController@delete')->name('admin.DummySnakeClass.delete');
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
        if (!$generator->exists) {
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
        if (!$generator->exists) {
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
        if (!$generator->exists) {
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
        if (!$generator->exists) {
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

namespace App\Http\Controllers\Admin;

use App\Models\DummyClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
/**
 * DummyDisplayName
 */
class DummyClassController extends Controller
{

    public function index(Request \$request): JsonResponse
    {
           \$create_start_time = \$request->get('create_start_time');
           \$create_end_time = \$request->get('create_end_time');
           <%for(item of DummyTableFields){%>
        <%if(item.can_search) { %>
            <%if(item.rule=='numeric') { %>
             \$<%=item.field_name%> = (int)\$request->get('<%=item.field_name%>');
             <%}else{%>
             \$<%=item.field_name%> = \$request->get('<%=item.field_name%>');
             <%}%>
         <%}%>
        <%}%>
           \$data = DummyClass::orderByDesc('id')
    <%for(item of DummyTableFields){%>
        <%if(item.can_search) { %>
             <%if(item.rule=='string') { %>
                    ->when(\$<%=item.field_name%>, fn (Builder \$query) => \$query->where('<%=item.field_name%>', 'like', "%{\$<%=item.field_name%>}%"))
             <%}else if(item.rule=='numeric'){%>
                    ->when(\$<%=item.field_name%>, fn (Builder \$query) => \$query->where('<%=item.field_name%>',  \$<%=item.field_name%>))
             <%}else{%>
                    ->when(\$<%=item.field_name%>, fn (Builder \$query) => \$query->where('<%=item.field_name%>', 'like', "%{\$<%=item.field_name%>}%"))
             <%}%>
         <%}%>
        <%}%>
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
            <%for(item of DummyTableFields){%>
                 <%if(item.rule=='string' && item.nullable==false) { %>
             '<%=item.field_name%>'=>'required',
                 <%}%>
            <%}%>
        ],[],[
            'id' => 'ID',
            <%for(item of DummyTableFields){%>
                 <%if(item.rule=='string') { %>
             '<%=item.field_name%>'=>'<%=item.field_display_name%>',
                 <%}%>
            <%}%>
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
        $index_temp = <<<stub
<script>

</script>
<template>
        <el-form ref="form" :model="form" label-width="60px">
            <el-row>
                <%for(item of DummyTableFields){%>
                    <%if(item.can_search && item.rule=='string') { %>
                     <el-col :span="4">
                        <el-form-item label="<%=item.field_display_name%>">
                            <el-input v-model="form.<%=item.field_name%>" @keyup.enter.native="getData()"></el-input>
                        </el-form-item>
                    </el-col>
                    <%}%>
                <%}%>
                <el-col :span="4">
                    <el-form-item>
                        <el-button type="primary" @click="getData()">查询</el-button>
                        <a href="{{ route('home.DummySnakeClass.update') }}" target="_blank"><el-button type="danger">添加</el-button></a>
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
            <%for(item of DummyTableFields){%>
                <%if(item.is_show_lists) { %>
                <el-table-column
                    prop="<%=item.field_name%>"
                    label="<%=item.field_display_name%>"
                    width="180">
                 </el-table-column>
                <%}%>
            <%}%>
            <el-table-column
                    fixed="right"
                    label="操作"
                    width="200">
                <template slot-scope="scope">
                    <a :href="'{{ route('home.DummySnakeClass.update') }}?id='+scope.row.id" target="_blank">
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
<style scoped lang="scss">
</style>
stub;
        $update_temp = <<<stub
<script>

</script>
<template>
    <div class="box-header">
        <el-header  id="content-header">
            <el-breadcrumb separator-class="el-icon-arrow-right">
                <el-breadcrumb-item><a href="{{ route('home.DummySnakeClass.index') }}">DummyDisplayName</a></el-breadcrumb-item>
                <el-breadcrumb-item>@{{ form.id?'编辑':'添加' }}</el-breadcrumb-item>
            </el-breadcrumb>
        </el-header>
        <el-main>
            <el-form ref="form" :model="form" :rules="rules" label-width="80px">
                <%for(item of DummyTableFields){%>
                    <%if(item.rule=='string') { %>
                    <el-form-item label="<%=item.field_display_name%>" prop="<%=item.field_name%>">
                        <el-input v-model="form.<%=item.field_name%>"></el-input>
                    </el-form-item>
                    <%}%>
                <%}%>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit('form')" :loading="submitLoading">确定</el-button>
                </el-form-item>
            </el-form>
        </el-main>
    </div>
</template>>
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
        return "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<%* soft delete *%>
<%if(DummyModelFields.soft_deletes){%>
use Illuminate\Database\Eloquent\SoftDeletes;
<%}%>

class DummyClass extends Model
{

    /** @use HasFactory<\Database\Factories\DummyClassFactory> */
    use HasFactory;
<%if(DummyModelFields.soft_deletes){%>
     use SoftDeletes;
<%}%>
     protected \$fillable = [<%for(item of DummyTableFields){%><%if('id'!=item.field_name) { %>'<%=item.field_name%>',<%}%><%}%>];

<%if(!DummyModelFields.timestamps){%>
     public \$timestamps = false;
<%}%>

<%for(relationship of DummyRelationShips){%>
    <%if('hasMany'==relationship.relationship) { %>
     public function <%=relationship.snake_plural_model%>(){
         return \$this->hasMany(<%=relationship.model%>::class<%if(relationship.foreign_key) { %>,'<%=relationship.foreign_key%>'<%}%>);
     }

    <%}else{%>
     public function <%=relationship.snake_model%>(){
         return \$this-><%=relationship.relationship%>(<%=relationship.model%>::class<%if(relationship.foreign_key) { %>,'<%=relationship.foreign_key%>'<%}%>);
     }

    <%}%>
<%}%>




}";
    }
}
