@extends('laravel-generator::layout')
@section('content')
        <el-container style=" border: 1px solid #eee">
            <el-aside width="200px">
                <el-menu :default-openeds="['1', '2']">
                    <el-submenu index="1">
                        <template slot="title"><i class="el-icon-tickets"></i>@{{ laravel_generators['className'] }}</template>
                        <el-menu-item-group>
                            <template slot="title">@lang('laravel-generator::generator.classInfo')</template>
                            <el-menu-item index="1-0" @click="insertEditor(dummyAttrs['classDisplayName'])" >
                                <span>@lang('laravel-generator::generator.classDisplayName')</span>
                                <el-popover placement="top-start" trigger="hover" >
                                    <p>@lang('laravel-generator::generator.classDisplayNameDesc')</p>
                                    <i slot="reference" class="el-icon-question"></i>
                                </el-popover>
                            </el-menu-item>
                            <el-menu-item index="1-1" @click="insertEditor(dummyAttrs['className'])" >
                                <span>@lang('laravel-generator::generator.className')</span>
                                <el-popover placement="top-start" trigger="hover" >
                                    <p>@lang('laravel-generator::generator.classNameDesc')</p>
                                    <i slot="reference" class="el-icon-question"></i>
                                </el-popover>
                            </el-menu-item>
                            <el-menu-item index="1-2" @click="insertEditor(dummyAttrs['camelClassName'])" >
                                <span>@lang('laravel-generator::generator.camelClassName')</span>
                                <el-popover placement="top-start" trigger="hover" >
                                    <p>@lang('laravel-generator::generator.camelClassNameDesc')</p>
                                    <i slot="reference" class="el-icon-question"></i>
                                </el-popover>
                            </el-menu-item>
                            <el-menu-item index="1-3" @click="insertEditor(dummyAttrs['snakeClassName'])" >
                                <span>@lang('laravel-generator::generator.SnakeClassName')</span>
                                <el-popover placement="top-start" trigger="hover" >
                                    <p>@lang('laravel-generator::generator.SnakeClassNameDesc')</p>
                                    <i slot="reference" class="el-icon-question"></i>
                                </el-popover>
                            </el-menu-item>
                            <el-menu-item index="1-4" @click="insertEditor(dummyAttrs['pluralClassName'])" >
                                <span>@lang('laravel-generator::generator.PluralClassName')</span>
                                <el-popover placement="top-start" trigger="hover" >
                                    <p>@lang('laravel-generator::generator.PluralClassNameDesc')</p>
                                    <i slot="reference" class="el-icon-question"></i>
                                </el-popover>
                            </el-menu-item>
                            <el-menu-item index="1-5" @click="insertEditor(dummyAttrs['snakePluralClassName'])" >
                                <span>@lang('laravel-generator::generator.SnakePluralClassName')</span>
                                <el-popover placement="top-start" trigger="hover" >
                                    <p>@lang('laravel-generator::generator.SnakePluralClassNameDesc')</p>
                                    <i slot="reference" class="el-icon-question"></i>
                                </el-popover>
                            </el-menu-item>
                        </el-menu-item-group>
                        <el-submenu index="1-6">
                            <template slot="title">Table Fields</template>
                        <el-menu-item-group title="field_name">
                            <el-menu-item index="1-2-1" @click="insertEditor(dummyAttrs['tableFields'])">
                                <span>Table Fields</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-10" @click="insertEditor('field_name')">
                                <span>field_name</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-2" @click="insertEditor('field_display_name')">
                                <span>field_display_name</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-3" @click="insertEditor('type')">
                                <span>type</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-4" @click="insertEditor('attach')">
                                <span>attach</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-5" @click="insertEditor('nullable')">
                                <span>nullable</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-6" @click="insertEditor('key')">
                                <span>key</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-7" @click="insertEditor('is_show_lists')">
                                <span>is_show_lists</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-8" @click="insertEditor('can_search')">
                                <span>can_search</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-9" @click="insertFunction('rule')">
                                <span>rule</span>
                            </el-menu-item>
                            <el-menu-item index="1-2-11" @click="insertFunction('fillable')">
                                <span>fillable</span>
                            </el-menu-item>
                        </el-menu-item-group>
                        </el-submenu>
                    </el-submenu>
                    <el-submenu index="2">
                        <template slot="title"><i class="el-icon-menu"></i>Model</template>
                        <el-menu-item-group>
                            <el-menu-item index="2-1" @click="insertFunction('primary_key')" >
                                primary_key
                            </el-menu-item>
                            <el-menu-item index="2-2" @click="insertFunction('timestamps')" >
                                timestamps
                            </el-menu-item>
                            <el-menu-item index="2-3" @click="insertFunction('soft_deletes')" >
                                soft_deletes
                            </el-menu-item>
                            <el-submenu index="2-4">
                                <template slot="title">Relationships</template>
                                <el-menu-item-group title="@lang('laravel-generator::generator.relationshipDesc')">
                                    <el-menu-item index="2-2-10" @click="insertEditor('relationship')">
                                        <span>relationship</span>
                                    </el-menu-item>
                                    <el-menu-item index="2-2-2" @click="insertEditor('model')">
                                        <span>model</span>
                                    </el-menu-item>
                                    <el-menu-item index="2-2-3" @click="insertEditor('camel_model')">
                                        <span>camel_model</span>
                                    </el-menu-item>
                                    <el-menu-item index="2-2-4" @click="insertEditor('snake_model')">
                                        <span>snake_model</span>
                                    </el-menu-item>
                                    <el-menu-item index="2-2-5" @click="insertEditor('snake_plural_model')">
                                        <span>snake_plural_model</span>
                                    </el-menu-item>
                                    <el-menu-item index="2-2-6" @click="insertEditor('foreign_key')">
                                        <span>foreign_key</span>
                                    </el-menu-item>
                                    <el-menu-item index="2-2-7" @click="insertEditor('with')">
                                        <span>with</span>
                                    </el-menu-item>
                                    <el-menu-item index="2-2-8" @click="insertEditor('can_search')">
                                        <span>can_search</span>
                                    </el-menu-item>
                                </el-menu-item-group>
                            </el-submenu>
                        </el-menu-item-group>
                    </el-submenu>
                </el-menu>
            </el-aside>
            <el-container>
                <el-main>
                    <el-form  label-position="right" :model="form" :rules="rules" ref="form" label-width="100px">
                        <el-row type="flex">
                            <el-col :span="5">
                                <el-form-item  label="@lang('laravel-generator::generator.templateName'):" prop="name">
                                    <el-input  v-model="form.name"></el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="5">
                                <el-form-item label="@lang('laravel-generator::generator.group'):" prop="template_id">
                                    <el-select v-model="form.template_id"
                                               filterable
                                               clearable
                                               allow-create
                                               placeholder="@lang('laravel-generator::generator.group')"
                                               no-data-text="@lang('laravel-generator::generator.noData')"
                                               @change="selectChange()">
                                        <el-option
                                                v-for="item in template_types"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value">
                                        </el-option>
                                    </el-select>
                                    <div class="el-form-item__error" style="color: #000000" v-if="showNote">
                                        @lang('laravel-generator::generator.ifNotExit')
                                    </div>
                                </el-form-item>
                            </el-col>
                            <el-col :span="4">
                                <el-form-item>
                                    <el-checkbox v-model="form.is_checked">
                                        @lang('laravel-generator::generator.templateIsChecked')
                                    </el-checkbox>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-row type="flex">

                            <el-col :span="8">
                                <el-form-item label="@lang('laravel-generator::generator.templatePath'):" prop="path">
                                    <el-input v-model="form.path"></el-input>
                                </el-form-item>
                            </el-col>

                            <el-col :span="8" style="margin-left: 10px">
                                <el-tag style="cursor: pointer" @click.native="addPathName('app/Http/Controllers/Home/','DummyClassController.php')">Controller</el-tag>
                                <el-tag style="cursor: pointer" @click.native="addPathName('resources/views/home/DummySnakeClass/','index.blade.php')" type="success">View</el-tag>
                                <el-tag style="cursor: pointer" @click.native="addPathName('app/Http/Requests/','DummyClassRequest.php')" type="info">Request</el-tag>
                                <el-tag style="cursor: pointer" @click.native="addPathName('resources/assets/js/DummySnakeClass/','index.vue')" type="warning">Vue</el-tag>
                                <el-tag style="cursor: pointer" @click.native="addPathName('app/Models/','DummyClass.php')" type="danger">Model</el-tag>
                            </el-col>
                        </el-row>
                        <el-row type="flex">

                            <el-col :span="8">
                                <el-form-item label="@lang('laravel-generator::generator.templateFileName'):" prop="file_name">
                                    <el-input v-model="form.file_name"></el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="8" style="margin-left: 10px">
                                <el-button type="text">@{{ fullPathName }}</el-button>
                            </el-col>
                        </el-row>
                        <el-row type="flex">
                                <div style="padding: 5pt">
                                    <el-button type="success" @click="insertFunction('if')" size="small" round><span style="font-size: 14px">if</span></el-button>
                                    <el-button type="success" @click="insertFunction('elseif')" size="small" round><span style="font-size: 14px">elseif</span></el-button>
                                    <el-button type="success" @click="insertFunction('for')" size="small" round><span style="font-size: 14px">for</span></el-button>
                                    <el-button type="success" @click="insertFunction('for')" size="small" round><span style="font-size: 14px">for</span></el-button>
                                    <el-button type="success" @click="insertFunction('tableFields')" size="small" round><span style="font-size: 14px">tableFields</span></el-button>
                                    <el-button type="success" @click="insertFunction('tableFieldsFor')" size="small" round><span style="font-size: 14px">For tableFields</span></el-button>
                                    <el-button type="success" @click="insertFunction('var')" size="small" round><span style="font-size: 14px">var</span></el-button>
                                    <el-button type="success" @click="insertFunction('rule')" size="small" round><span style="font-size: 14px">rule</span></el-button>
                                    <el-button type="success" @click="insertFunction('relationships')" size="small" round><span style="font-size: 14px">relationships</span></el-button>
                                </div>
                                <el-button type="danger" class="subButton" @click="submitForm('form')" :disabled="submitDisabled">
                                    @lang('laravel-generator::generator.submit')
                                </el-button>
                        </el-row>
                        <el-row type="flex" style="margin-top: 5px">
                                <div id="container" style="width:45%;min-width:600px;height:600px;border:1px solid grey;float: left;margin-right: 10px"></div>
                                <div id="containerShow" style="width:45%;min-width:600px;height:600px;border:1px solid grey;float: left"></div>
                        </el-row>
                    </el-form>
                </el-main>

            </el-container>
        </el-container>
@endsection
@section('css')
    <style>
     .subButton{
         width: 150px;
         height: 50px;
         margin-left: 166px;
     }
     .margin_top{
         padding: 10px;
     }
    </style>
@endsection
@section('js')
    <link rel="stylesheet" data-name="vs/editor/editor.main" href="/vendor/laravel-generator/vs/editor/editor.main.css">
    <script src="/vendor/laravel-generator/vs/loader.js"></script>
    <script src="/vendor/laravel-generator/js/baiduTemplate.js"></script>
    <script src="/vendor/laravel-generator/vs/editor/editor.main.nls.js"></script>
    <script src="/vendor/laravel-generator/vs/editor/editor.main.js"></script>
    <script src="/vendor/laravel-generator/vs/basic-languages/java/java.js"></script>
    <script src="/vendor/laravel-generator/vs/base/worker/workerMain.js "></script>
    <script>
        var vm = new Vue({
          el: '#app',
          data() {
              return {
                  message: 'Hello',
                  editor:{},
                  editor2:{},
                  template:'',
                  template_types:@json($template_types),
                  laravel_generators:@json($laravel_generators),
                  dummyAttrs:@json($dummyAttrs),
                  functions:@json($functions),
                  showNote:true,
                  form:@json($form),
                  bean:{},
                  submitDisabled:false,
                  rules:{
                      name: [
                          { required: true, message: '@lang('laravel-generator::generator.templateName') @lang('laravel-generator::generator.required')', trigger: 'blur' },
                      ],
                      template_id: [
                          { required: true, message: '@lang('laravel-generator::generator.group') @lang('laravel-generator::generator.required')', trigger: 'change' },
                      ],
                      path: [
                          { required: true, message: '@lang('laravel-generator::generator.templatePath') @lang('laravel-generator::generator.required')', trigger: 'blur' },
                      ],
                      file_name: [
                          { required: true, message: '@lang('laravel-generator::generator.templateFileName') @lang('laravel-generator::generator.required')', trigger: 'blur' },
                      ],
                  }
              }
          },
          methods: {
              //插入内容
              insertEditor(text){
                  var selection = this.editor.getSelection();
                  var range = new monaco.Range(selection.startLineNumber, selection.startColumn, selection.endLineNumber, selection.endColumn);
                  var id = { major: 1, minor: 1 };
                  var op = {identifier: id, range: range, text: text, forceMoveMarkers: true};
                  this.editor.executeEdits("my-source", [op]);
                  this.editor.focus();
              },
              /**
               * 处理回车换行
               */
              handleEnterKey(codeTemplate) {
                    codeTemplate = this.replaceAll(codeTemplate,"\t","    ");
                    var returnCode = "";
                    var codes = codeTemplate.split("\n");
                    for(var i = 0 ; i < codes.length; i ++) {
                        if (codes[i].trim().indexOf("<%") == 0) {
                            if(codes[i].trim().indexOf('<%\=') == 0){
                                    returnCode+=codes[i].trim()+ "``";
                            }else {
                                returnCode += codes[i].trim()
                            }
                        }else{
                            returnCode+=codes[i]+ "``";
                        }
                    }

                    return returnCode;
              },
              /**
               * 替换全部
               */
              replaceAll(strVal,search,replace){
                  return strVal.replace(new RegExp(search,"gm"),replace);
              },
              /**
               * 选择模板分类
               */
              selectChange(){
                this.showNote=false;
              },
              /**
               * 插入函数
               */
              insertFunction(code){
                  this.insertEditor(this.functions[code]);
              },
              addPathName(path,file_name){
                  console.log(path,file_name);
                this.form.path=path;
                this.form.file_name=file_name;
              },
              //提交表单
              submitForm(form){
                  this.showNote=false;
                  this.$refs[form].validate((valid) => {
                      if (valid) {
                          if(!this.form.template){
                             this.$message.error('@lang('laravel-generator::generator.template') @lang('laravel-generator::generator.required')');
                          }
                          this.submitDisabled=true;
                          axios.post('{{ route('generator.template.save') }}',this.form).then(function(res){
                              console.log(res);
                              var data=res.data;
                              if(data.errcode==0){
                                  vm.$message.success('@lang('laravel-generator::generator.submitSuccess')');
                              }else{
                                  vm.$message.error(data.message);
                              }
                              vm.submitDisabled=false;
                          });
                      } else {
                          return false;
                      }
                  });
              },
              //替换掉所有的模板变量
              replaceDummyClass(str){
                  for (var index in this.dummyAttrs){
                      if(index=='tableFields' || index=='modelFields' || index=='relationships'){
                          continue;
                      }
                      str=str.replace(new RegExp(this.dummyAttrs[index],"gm"),this.laravel_generators[index]);
                  }
                  return str;
              }
          },
           computed: {
                fullPathName: function () {
                        var path=this.replaceDummyClass(this.form.path.trim('/'));
                        path=path?path+'/':'';
                        return  path + this.replaceDummyClass(this.form.file_name.trim('/'));
                }
           },
          mounted(){
                baidu.template.ESCAPE = false;
                var model = monaco.editor.createModel('','java');
                this.editor = monaco.editor.create(document.getElementById('container'), {
                    model: model,
                });
                this.editor2 = monaco.editor.create(document.getElementById('containerShow'), {
                    value: '',
                    language: 'java',
                    readOnly:true
                });
                this.editor.onDidChangeModelContent(e => {
                    var data={
                        Template:'BaiduTemplate',
                        DummyTableFields:this.laravel_generators['tableFields'],
                        DummyModelFields:this.laravel_generators['modelFields'],
                        DummyRelationShips:this.laravel_generators['relationships'],
                    }
                    try {
                        this.form.template=this.editor.getValue();
                        var code=this.handleEnterKey(this.editor.getValue());
                        code=this.replaceDummyClass(code);
                        var temp=baidu.template(code, data);
                        html = this.replaceAll(temp,"``","\n");
                        html=this.replaceAll(html,'&#92;','\\');
                        html=this.replaceAll(html,'&#39;','\'');
                        this.editor2.setValue(html);
                    }catch (e) {
                        this.$message({
                            dangerouslyUseHTMLString: true,
                            type: 'error',
                            message: "@lang('laravel-generator::generator.templateError')"
                        });
                        $("#errorMsg").html();
                    }

                });
                this.editor.setValue(this.form.template);
          }
        });
    </script>
@endsection