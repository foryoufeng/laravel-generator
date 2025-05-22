@extends('laravel-generator::layout')
@section('content')
        <el-container style=" border: 1px solid #eee">
            <el-aside width="200px">
                <el-menu :default-openeds="['1', '2','3']">
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
                    {{--模型可用的操作方法--}}
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
                    {{--自定义变量值--}}
                    <el-submenu index="3">
                        <template slot="title"><i class="el-icon-menu"></i>Custom Keys</template>
                        <el-menu-item-group>
                            @foreach($customKeys as $k=>$val)
                                <el-menu-item index="3-{{ $loop->index }}" @click="insertEditor('{{ "{{\$customKeys[\'".$k."\']\}\}" }}')" >
                                    {{ $k }}
                                </el-menu-item>
                            @endforeach
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
                                               @visible-change="visibleChange"
                                               @change="selectChange()" class="group-select">
                                        <el-option class="options"
                                                v-show="!labelsVisible"
                                                v-for="(item,index) in template_types"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value">
                                            <span style="float: left">@{{ item.label }}</span>
                                            <span style="float: right; color: #8492a6; font-size: 13px" class="delete-icon"><i class="el-icon-edit-outline" @click.prevent.stop="showForm(item.value,index)"></i></span>
                                        </el-option>
                                        <div class="selectLabels s_flex fd-cl ai_ct" v-show="labelsVisible" style="margin-top: 10px;">
                                            <div style="width: 100%;margin-bottom: 10px">
                                                <em class="iconfont icon-flow" style="cursor: pointer;" @click="labelsVisible = false"></em>
                                                <span v-if="labelForm.id>0">@lang('laravel-generator::generator.edit')</span>
                                                <span v-else>@lang('laravel-generator::generator.add')</span>
                                            </div>
                                            <div style="width: 100%;margin-bottom: 10px" class="s_flex fd-cl">
                                                <span><em style="color: red;margin-right: 5px;">*</em>@lang('laravel-generator::generator.name')：</span>
                                                <el-input v-model="labelForm.name" :maxlength="6" size="small" style="width: 160px"></el-input>
                                            </div>
                                        </div>
                                        <div style="padding: 0 20px;position:sticky;bottom: 0;background: #fff;">
                                            <el-button v-if="!labelsVisible" type="text" @click="showForm(0,-1)">@lang('laravel-generator::generator.add')</el-button>
                                            <el-button v-if="labelsVisible"  type="text" @click="labelsVisible = false">@lang('laravel-generator::generator.cancel')</el-button>
                                            <el-button v-if="labelsVisible" type="danger" size="mini" @click="updateLabel">@lang('laravel-generator::generator.sure')</el-button>
                                        </div>
                                    </el-select>
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
                                @foreach($tags as $tag)
                                    <el-tag style="cursor: pointer" @click.native="addPathName('{{ $tag["path"] }}','{{ $tag["file"] }}')" type="{{ $tag["type"] }}">{{ $tag["name"] }}</el-tag>
                                @endforeach
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
                                    <el-button type="success" @click="insertFunction('rule')" size="small" round><span style="font-size: 14px">rule</span></el-button>
                                    <el-button type="success" @click="insertFunction('relationships')" size="small" round><span style="font-size: 14px">relationships</span></el-button>
                                </div>
                        </el-row>
                        <el-row type="flex" style="margin-top: 5px;margin-bottom: 15px">
                                <div id="container" style="width:45%;min-width:600px;height:600px;border:1px solid grey;float: left;margin-right: 10px"></div>
                                <div id="containerShow" style="width:45%;min-width:600px;height:600px;border:1px solid grey;float: left"></div>
                        </el-row>
                        <el-button type="danger" class="subButton" @click="submitForm('form')" :disabled="submitDisabled">
                            @lang('laravel-generator::generator.submit')
                        </el-button>
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
     }
     .margin_top{
         padding: 10px;
     }
     .selectLabels {
         padding: 5px 20px;
     }
     .delete-icon{
         visibility: hidden;
     }
     .options:hover .delete-icon{
         visibility: visible;
     }
     .el-select,.group-select {
         user-select: none;
         -webkit-user-select: none;
         -ms-user-select: none;
     }
    </style>
@endsection
@section('js')
    <link rel="stylesheet" data-name="vs/editor/editor.main" href="/laravel-generator/assets/vs/editor/editor.main.css">
    <script src="/laravel-generator/assets/vs/loader.js"></script>
    <script src="/laravel-generator/assets/vs/editor/editor.main.nls.js"></script>
    <script src="/laravel-generator/assets/vs/editor/editor.main.js"></script>
    <script src="/laravel-generator/assets/vs/basic-languages/java/java.js"></script>
    <script src="/laravel-generator/assets/vs/base/worker/workerMain.js "></script>
    <script>
        var vm = new Vue({
          el: '#app',
          data() {
              return {
                  labelsVisible:false,
                  // labelsVisible:true,
                  editor:{},
                  editor2:{},
                  template:'',
                  language_value:'{{$language_value}}',
                  language_options: [{
                      value: 'zh_CN',
                      label: '简体中文'
                  }, {
                      value: 'en',
                      label: 'English'
                  }
                  ],
                  template_types:@json($template_types),
                  laravel_generators:@json($laravel_generators),
                  dummyAttrs:@json($dummyAttrs),
                  functions:@json($functions),
                  showNote:true,
                  form:@json($form),
                  bean:{},
                  index:0,
                  labelForm:{
                      id:0,
                      name:''
                  },
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
              handleCommand(command){
                  window.location.href = '{{ route('generator.index') }}/'+command
              },
              showForm(id,index){
                  this.labelsVisible = true
                  this.labelForm.id = id;
                  if(id>0){
                      this.labelForm.name = this.template_types[index].label
                  }
                  this.index = index
              },
              updateLabel(){
                  axios.post('{{ route('generator.template.updateType') }}',this.labelForm).then(function(res){
                      var data=res.data;
                      if(data.errcode==0){
                          vm.labelsVisible = false
                          if(vm.labelForm.id>0){
                              vm.$set(vm.template_types,vm.index,{
                                  label: data.data.name,
                                  value: data.data.id
                              })
                          }else{
                              vm.template_types.push({ label: data.data.name, value: data.data.id });
                          }
                      }else{
                          vm.$message.error(data.message);
                      }
                      vm.submitDisabled=false;
                  });
              },
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
               * 选择模板分类
               */
              selectChange(){
                this.showNote=false;
              },
              visibleChange(flag){
                  if(!flag){
                      this.labelsVisible = false
                      this.labelForm = {
                          id:0,
                          name:'',
                      }
                  }
              },
              /**
               * 插入函数
               */
              insertFunction(code){
                  this.insertEditor(this.functions[code]);
              },
              addPathName(path,file_name){
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
                              const data=res.data;
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
              },
              compile(template){
                  axios.post('{{ route('generator.template.compile') }}',{'template':template}).then(function(res){
                      const data=res.data;
                      if(data.errcode==0){
                          vm.editor2.setValue(data.data.template);
                      }else{
                          vm.$message.error(data.message);
                      }
                  });
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
                var model = monaco.editor.createModel('','java');
                this.editor = monaco.editor.create(document.getElementById('container'), {
                    model: model,
                });
                this.editor2 = monaco.editor.create(document.getElementById('containerShow'), {
                    value: '',
                    language: 'java',
                    readOnly:true
                });
                this.editor.onDidBlurEditorText(e => {
                    console.log("editor change")
                    let template = this.editor.getValue();
                    this.form.template = template;
                    this.compile(template);
                });
                //编辑器赋值
                this.editor.setValue(this.form.template);
                this.compile(this.form.template);
          }
        });
    </script>
@endsection
