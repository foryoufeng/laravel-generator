<?php
/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 1/14/19
 * Time: 10:53 AM.
 */

namespace Foryoufeng\Generator\Database;

use Illuminate\Database\Seeder;
use Foryoufeng\Generator\Models\LaravelGenerator;
use Foryoufeng\Generator\Models\LaravelGeneratorType;

class GeneratorSeeder extends Seeder
{
    public function run()
    {
        $this->addModel();

        $this->addControllers();

        $this->addViews();
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
            $generator->path = 'app/';
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
            'name' => 'homeController',
        ]);
        $controllerTemps = $this->getControllersTemplate();
        if (!$generator->exists) {
            $generator->path = 'app/Http/Controllers/Home/';
            $generator->file_name = 'DummyClassController.php';
            $generator->is_checked = 1;
            $generator->template = $controllerTemps['home'];
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
            $generator->path = 'resources/views/home/DummySnakeClass/';
            $generator->file_name = 'index.blade.php';
            $generator->is_checked = 1;
            $generator->template = $viewTemp['index'];
            $generator->template_id = $type->id;
            $generator->save();
        }
        $generator = LaravelGenerator::firstOrNew([
            'name' => 'update_view',
        ]);
        if (!$generator->exists) {
            $generator->path = 'resources/views/home/DummySnakeClass/';
            $generator->file_name = 'update.blade.php';
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

namespace App\Http\Controllers\Home;

use App\DummyClass;
use Illuminate\Http\Request;
/**
 * DummyDisplayName
 */
class DummyClassController extends Controller
{
    
    public function index(Request \$request)
    {
        \$DummySnakePluralClass=DummyClass::paginate(15);
        
        return view('home.DummySnakeClass.index',[
            'lists'=>\$DummySnakePluralClass
        ]);
    }

    public function show(Request \$request)
    {
        \$DummySnakeClass=DummyClass::find(1);

        return view('home.DummySnakeClass.show',[
            'item'=>\$DummySnakeClass
        ]);
    }
}
stub;

        return [
            'home' => $homeTemp,
        ];
    }

    /**
     * @return array
     */
    private function getViewsTemplate()
    {
        $index_temp = <<<stub
<tr>
<%for(item of DummyTableFields){%>
    <%if(item.is_show_lists) { %>
    <td><%=item.field_display_name%></td>
    <%}%>
<%}%>
</tr>
@foreach (\$datas as \$data)
<tr>
<%for(item of DummyTableFields){%>
    <%if(item.is_show_lists) { %>
    <td>{{ \$data-><%=item.field_name%> }}</td>
    <%}%>
<%}%>
</tr>
<tr>
@endforeach
stub;
        $update_temp = <<<stub
<tr>
<%for(item of DummyTableFields){%>
    <%if(item.is_show_lists) { %>
    <td><%=item.field_display_name%></td>
    <%}%>
<%}%>
</tr>
@foreach (\$datas as \$data)
<tr>
<%for(item of DummyTableFields){%>
    <%if(item.is_show_lists) { %>
    <td>{{ \$data-><%=item.field_name%> }}</td>
    <%}%>
<%}%>
</tr>
<tr>
@endforeach
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
        return "
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
<%* soft delete *%>
<%if(DummyModelFields.soft_deletes){%>
use Illuminate\Database\\Eloquent\SoftDeletes;
<%}%>

class DummyClass extends Model
{
<%* 模板注释 *%>
<%* Template annotation *%>

<%if(DummyModelFields.soft_deletes){%>
     use SoftDeletes;
<%}%>
<%* primary_key *%>
<%if('id'!=DummyModelFields.primary_key){%>
     protected \$primaryKey = '<%=DummyModelFields.primary_key%>';

<%}%>
     <%* fillable *%>
     protected \$fillable = [<%for(item of DummyTableFields){%><%if('id'!=item.field_name) { %>'<%=item.field_name%>',<%}%><%}%>];

<%if(!DummyModelFields.timestamps){%>
     public \$timestamps = false;

<%}%>
 <%* add relation *%>
<%for(relationship of DummyRelationShips){%>
    <%if('hasMany'==relationship.relationship) { %>
     public function <%=relationship.snake_plural_model%>(){
         return \$this->hasMany(<%=relationship.model%>::class <%if(relationship.foreign_key) { %>,'<%=relationship.foreign_key%>'<%}%>);
     }
     
    <%}else{%>
     public function <%=relationship.snake_model%>(){
         return \$this-><%=relationship.relationship%>(<%=relationship.model%>::class <%if(relationship.foreign_key) { %>,'<%=relationship.foreign_key%>'<%}%>);
     }

    <%}%>
<%}%>




}";
    }
}
