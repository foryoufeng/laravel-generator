@extends('laravel-generator::layout')
@section('content')
    <el-tabs type="border-card" v-model="tab" @tab-click="clickTab">
        {{--generator logs--}}
        @include('laravel-generator::generator_logs')
        {{--generator--}}
        @include('laravel-generator::generator')
        {{--migrate--}}
        @include('laravel-generator::generator_migrate')
        {{--templates--}}
        @include('laravel-generator::template_lists')
    </el-tabs>
@endsection
@section('css')
    <style>
        .subButton{
            width: 150px;
            height: 50px;
            margin-left: 30px;
        }
        .margin_top{
            padding: 10px;
        }
        #files .el-input__inner {
            width: auto;
            width: 450px; /* 设置最小宽度 */
            padding: 4px;
        }
    </style>
@endsection
@section('js')
    <script>
        var vm =new Vue({
            el: '#app',
            data:{
                loadding:false,
                isEditing:false,
                selectedTableValue:'',
                dialogTableVisible:false,
                logTitle:'',
                logRow:{},
                dbTypes:@json($dbTypes),
                keys: [
                    {
                    value: 'null',
                    label: 'NULL'
                }, {
                    value: 'unique',
                    label: 'unique'
                }, {
                    value: 'index',
                    label: 'index'
                }
                ],
                language_value:'{{$language_value}}',
                language_options: [{
                    value: 'zh_CN',
                    label: '简体中文'
                }, {
                    value: 'en',
                    label: 'English'
                }
                ],
                //外键约束关系
                onDeleteUpdate: [
                    {
                    value: 'cascade',
                    label: 'CASCADE'
                }, {
                    value: 'set null',
                    label: 'SET NULL'
                }, {
                    value: 'no action',
                    label: 'NO ACTION'
                }, {
                    value: 'restrict',
                    label: 'RESTRICT'
                  }
                ],
                //可用的关系
                relationships:[
                    {
                        value: 'belongsTo',
                        label: 'belongsTo'
                    }, {
                        value: 'hasOne',
                        label: 'hasOne'
                    }, {
                        value: 'hasMany',
                        label: 'hasMany'
                    }, {
                        value: 'belongsToMany',
                        label: 'belongsToMany'
                    }
                ],
                //数据格式
                fieldRules:@json($rules),
                isShowForeign:false,
                isShowRelationship:false,
                referencesFileds:[],
                //搜索数据
                search:{
                    name:''
                },
                tab:'{{ $tab }}',
                logSearch:{
                    model_name:'',
                    display_name:'',
                    creator:''
                },
                //模板数据
                logs:[],
                pageInfo:{},
                templates:[],
                //数据库表数据
                tables:@json($tables),
                //可用的假属性
                dummyAttrs:@json($dummyAttrs),
                dummyValues:[],
                //模板列表
                template_types:@json($template_types['datas']),
                ruleForm: {},
                migrateForm:{
                    prefix:'add',
                    tableName:'',
                    doMigrate:[
                        'migration',
                        'migrate'
                    ],
                    table_fields:[{
                        field_name:'',
                        change:false,
                        type:'string',
                        nullable:false,
                        key:'',
                        default:'',
                        comment:'',
                        rule:''
                    }],
                },
                rules: {
                    prefix: [
                        { required: true, message: 'prefix @lang('laravel-generator::generator.required')', trigger: 'blur' },
                    ],
                    modelName: [
                        { required: true, message: 'model @lang('laravel-generator::generator.required')', trigger: 'blur' },
                    ],
                    modelDisplayName: [
                        { required: true, message: '@lang('laravel-generator::generator.displayName') @lang('laravel-generator::generator.required')', trigger: 'blur' },
                    ],
                    tableName: [
                        { required: true, message: 'table name @lang('laravel-generator::generator.required')', trigger: 'blur' },
                    ],
                }
            },
            computed: {
                migrateName() {
                    var name=this.migrateForm.prefix+'_';
                    if(this.migrateForm.table_fields.length>2){
                        name+=this.migrateForm.table_fields[0]['field_name']+'AndMore_'
                    }else{
                        var column='';
                        for (var index in this.migrateForm.table_fields){
                            var field=this.migrateForm.table_fields[index]['field_name'];
                            if(field){
                                name+=field+'_';
                            }
                        }
                    }
                    name+='to_'+this.migrateForm.tableName+'_table';
                    return name;
                },
                foreignFileds() {
                    var fields=[];
                    for (var index in this.ruleForm.table_fields) {
                        var field=this.ruleForm.table_fields[index]['field_name'];
                        if(field){
                            fields.push({
                                'label':field,
                                'value':field,
                            });
                        }
                    }
                    return fields;
                }
            },
            watch:{
                //监听模型的变化
                'ruleForm.modelName': function(val){
                   if(val){
                       //设置模型的名称
                       this.ruleForm.modelName=this.ruleForm.modelName[0].toUpperCase()+this.ruleForm.modelName.substring(1);
                       var route='{{ route('generator.dummyValues',['name'=>'']) }}'+'/'+this.ruleForm.modelName;
                       //获取请求的数据
                       axios.get(route).then(function(res){
                           if(res.data.errcode==0){
                               var dummyValues=res.data.data;
                               vm.dummyValues=dummyValues;
                               var i=0;
                               for (item of vm.template_types){
                                   for (template of item.templates){
                                       //替换路径
                                       var path=vm.replaceDummyClass(template.path,dummyValues);
                                       //替换文件名
                                       var file_name=vm.replaceDummyClass(template.file_name,dummyValues);
                                       template.file_real_name=path+file_name;
                                   }
                                   vm.$set(vm.template_types,i,item);
                                   i++;
                               }
                           }
                       })

                   }
                },
                //监听关联关系的变化
                'ruleForm.relationships': {
                    handler(val, oldVal){
                        if(val && this.ruleForm.modelName){
                            for(relationship of this.ruleForm.relationships){
                                if(relationship.relationship &&relationship.model){
                                        relationship.model=relationship.model[0].toUpperCase()+relationship.model.substring(1);
                                        var foreign_key='';
                                        if(relationship.foreign_key){
                                            foreign_key=',\''+relationship.foreign_key+'\'';
                                        }
                                        this.getDummyValues(relationship.model).then(function (res) {
                                            relationship.camel_model=res['DummyCamelClass'];
                                            relationship.snake_model=res['DummySnakeClass'];
                                            relationship.snake_plural_model=res['DummySnakePluralClass'];
                                        });
                                        relationship.relation=vm.ruleForm.modelName+' add: return $this->'+relationship.relationship+'('+relationship.model+'::class'+foreign_key+')';
                                        if(relationship.reverse){
                                            relationship.reverseRelation=relationship.model+' add:  return $this->'+relationship.reverse+'('+vm.ruleForm.modelName+'::class'+foreign_key+')';
                                        }else{
                                            relationship.reverseRelation='';
                                        }
                                  }
                                }

                            }
                    },
                    deep:true
                },
            },
            created() {
                this.ruleForm = this.getRuleForm()
            },
            mounted(){
                //加载数据
                this.getData();
                this.getLogs()
            },
            methods:{
                handleCommand(command){
                    window.location.href = '{{ route('generator.index') }}/'+command
                },
                logDetail(row){
                    this.logRow = JSON.parse(row.configs);
                    this.dialogTableVisible = true
                    this.logTitle = '@lang('laravel-generator::generator.modelName'):'+row.model_name
                },
                clickTab(tab){
                    if(tab.name ==='log'){
                        const page = this.logSearch.page
                        this.getLogs(page)
                    }
                },
                getRuleForm(){
                  return  {
                      id: 0,
                      modelName: '',
                      modelDisplayName: '',
                      create:[
                          'migration',
                          'migrate',
                          'ide-helper',
                      ],
                      primary_key:'id',
                      timestamps:true,
                      foreigns:[],
                      relationships:[],
                      table_fields:[{
                          field_name:'',
                          field_display_name:'',
                          type:'string',
                          nullable:false,
                          is_list_display:true,
                          can_search:false,
                          key:'',
                          default:'',
                          comment:'',
                          attach:'',
                          rule:'',
                      }],
                      soft_deletes:false,
                      //选中的模板数据
                      templates:{
                          @foreach($template_types['datas'] as $templates)
                          '{{ $templates['name'] }}':@json($templates['checked']),
                          @endforeach
                      },
                  }
                },
                editLog(row,id){
                    const ruleForm = JSON.parse(row.configs);
                    if(ruleForm){
                        ruleForm.id = id
                        this.switchTab('generator',ruleForm)
                    }
                },
                switchTab(tab,ruleForm) {
                    this.tab = tab;
                    this.ruleForm = ruleForm
                    //选中的模板数据
                    this.ruleForm.templates = {
                        @foreach($template_types['datas'] as $templates)
                        '{{ $templates['name'] }}':@json($templates['checked']),
                        @endforeach
                    }
                },
                handlePage(val){
                    this.getLogs(val);
                },
                setPageInfo(pageInfo){
                    this.pageInfo.total=pageInfo.total;
                    this.pageInfo.per_page=pageInfo.per_page;
                    this.pageInfo.current_page=pageInfo.current_page;
                },
                getLogs(page = 1){
                    this.logSearch.page = page
                    axios.get('{{ route('generator.logs') }}',{params:this.logSearch}).then(res=> {
                        let data=res.data;
                        if(data.errcode==0){
                            this.logs=data.data.data;
                            this.setPageInfo(data.data);
                        }else {
                            this.logs=[];
                        }
                    })
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
                 * 获取解析模板的数据
                 */
                getTemplateCode(template,data){
                    var code=this.handleEnterKey(template);
                    this.dummyValues['DummyDisplayName']=vm.ruleForm.modelDisplayName;
                    code=this.replaceDummyClass(code,this.dummyValues);
                    var html=this.replaceAll(code,"``","\n")
                    html=this.replaceAll(html,'&#92;','\\')
                    return this.replaceAll(html,'&#39;','\'');
                },
                /**
                 * 获取解析的数据
                 */
                getTemplateData(){
                    var modelFields={
                        primary_key:this.ruleForm.primary_key,
                        timestamps:this.ruleForm.timestamps,
                        soft_deletes:this.ruleForm.soft_deletes,
                    };
                    return {
                        DummyTableFields:this.ruleForm.table_fields??[],
                        DummyModelFields:modelFields,
                        DummyRelationShips:this.ruleForm.relationships??[],
                    }
                },
                /**
                 * 获取模型的转换数据
                 */
                async getDummyValues(model){
                    var route='{{ route('generator.dummyValues',['name'=>'']) }}'+'/'+model;
                    let res=await axios.get(route);
                    return new Promise(function (resolve,reject) {
                        if(res.data.errcode==0){
                            resolve(res.data.data)
                        }else{
                            reject(new Error('request error '));
                        }
                    })
                },
                /**
                 * 替换全部
                 */
                replaceAll(strVal,search,replace){
                    return strVal.replace(new RegExp(search,"gm"),replace);
                },
                //获取模板列表数据
                getData(){
                    axios.get('{{ route('generator.template.index') }}',{params:this.search}).then(function (res) {
                        var data=res.data;
                        if(data.errcode==0){
                            vm.templates=data.data;
                        }else {
                            vm.templates=[];
                        }
                    })
                },
                //替换掉所有的模板变量
                replaceDummyClass(str,dummyValues){
                    for (var index in this.dummyAttrs){
                        if(index=='tableFields' || index=='modelFields' || index=='relationships'){
                            continue;
                        }
                        str=str.replace(new RegExp(this.dummyAttrs[index],"gm"),dummyValues[this.dummyAttrs[index]]);
                    }
                    return this.replaceCustomDummy(str);
                },
                //替换自定义变量
                replaceCustomDummy(str){
                    var customDummys=@json($customDummys);
                    for (var index in customDummys){
                        str=str.replace(new RegExp(index,"gm"),customDummys[index]);
                    }
                    return str;
                },
                //添加外键约束
                addForeign(){
                    var flen=this.ruleForm.foreigns.length;
                    var tlen=this.ruleForm.table_fields.length;
                    this.isShowForeign=true;
                    if(tlen>flen){
                        this.ruleForm.foreigns.push({
                            foreign:'',
                            references:'',
                            on:''
                        });
                    }
                },
                //添加关联关系
                addRelationship(){
                    this.isShowRelationship=true;
                    this.ruleForm.relationships.push({
                        relationship:'belongsTo',
                        model:'',
                        foreign_key:'',
                        reverse:'',
                    });
                },
                //删除关联关系的处理
                deleteRelationship(index){
                    this.ruleForm.relationships.splice(index,1);
                },
                //监听外键数据的处理
                onForeignChange(index){
                    var _table=this.ruleForm.foreigns[index].on;
                    var result=this.tables.find(function(item){
                        return item.name== _table;
                    });
                    this.referencesFileds[index]=result.columns;
                },
                handleTableSelected(table){
                    if(table){
                        axios.get('{{ route('generator.create_by_table')  }}/'+table).then(res=> {
                            if(res.data.errcode===0){
                                this.switchTab('generator',res.data.data)
                            }else{
                                this.$message.error(res.data.message);
                            }
                        });
                    }else {
                        this.isEditing = false;
                    }
                },
                handleBlur() {
                    // 失去焦点后退出编辑模式
                    // this.isEditing = false;
                },
                //删除外键的处理
                deleteForeign(index){
                    this.ruleForm.foreigns.splice(index,1);
                    this.referencesFileds.splice(index,1);
                },
                //删除模板
                deleteTemplate(id){
                    this.$confirm('@lang('laravel-generator::generator.confirmDelete')', '@lang('laravel-generator::generator.notice')', {
                        confirmButtonText: '@lang('laravel-generator::generator.sure')',
                        cancelButtonText: '@lang('laravel-generator::generator.cancel')',
                        type: 'warning'
                    }).then(() => {
                            axios.post('{{ route('generator.template.delete')  }}',{id:id}).then(function (res) {
                                if(res.data.errcode==0){
                                    var href='{{ route('generator.index') }}?tab=templates'
                                    window.location.href=href;
                                }else{
                                    vm.$message.error(res.data.message);
                                }
                            });
                        }).catch(() => {});
                },
                deleteLog(id){
                    this.$confirm('@lang('laravel-generator::generator.confirmDelete')', '@lang('laravel-generator::generator.notice')', {
                        confirmButtonText: '@lang('laravel-generator::generator.sure')',
                        cancelButtonText: '@lang('laravel-generator::generator.cancel')',
                        type: 'warning'
                    }).then(() => {
                        axios.post('{{ route('generator.deleteLog')  }}',{id:id}).then(res=> {
                            if(res.data.errcode==0){
                               this.getLogs()
                            }else{
                                vm.$message.error(res.data.message);
                            }
                        });
                    }).catch(() => {});
                },
                //提交generator表单
                submitForm(formName,submit_type='') {
                    let fieldFlag = false
                    let primary_key=this.ruleForm.primary_key;
                    if(primary_key){
                        //防止添加字段和主键重复
                        fieldFlag = this.ruleForm.table_fields.find(function(item){
                            return item.field_name === primary_key
                        });
                    }
                    if(fieldFlag){
                        let message='primary_key: '+primary_key+'@lang('laravel-generator::generator.hasExists'),'+'@lang('laravel-generator::generator.delete')' +'@lang('laravel-generator::generator.fieldName')(Field name) '+primary_key
                        this.$message.error(message);
                        return;
                    }
                    this.ruleForm.submit_type = submit_type
                    this.$refs[formName].validate((valid) => {
                        if (valid) {
                            //获取选中的模板
                            let check_templates=this.ruleForm.templates;
                            let generator_templates=[];
                            //所有可用的模板
                            for (item of this.template_types){
                                let all_templates=item.templates;
                                let check_template_ids=check_templates[item['name']];
                                for (id of check_template_ids){
                                        let temp=all_templates.find(function (t) {
                                            return t.id==id;
                                        });
                                        //获取解析的模板数据
                                        let generator_template={
                                            file_real_name:temp.file_real_name,
                                            template:temp.template
                                        };
                                        generator_templates.push(generator_template);
                                }
                            }
                            this.ruleForm.generator_templates=generator_templates;
                            this.loadding=true;
                            axios.post('{{  route('generator.store') }}',this.ruleForm).then(res=>{
                                if(res.data.errcode===0){
                                    const data=res.data.data;
                                    let message='';
                                    for(x in data){
                                        message+='<p>'+data[x]+"</p><br>";
                                    }
                                    this.$message({
                                        title: 'success',
                                        dangerouslyUseHTMLString: true,
                                        message: message,
                                        center: true,
                                        type: 'success',
                                        duration:5000
                                    });
                                }else{
                                    vm.$message({
                                        title: 'error',
                                        dangerouslyUseHTMLString: true,
                                        message: res.data.message,
                                        center: true,
                                        type: 'error',
                                        duration:8000
                                    });
                                }
                                vm.loadding=false;
                            }).catch((error) => {
                                vm.loadding=false;
                                this.$message.error(error);
                            });
                        } else {
                            return false;
                        }
                    });
                },
                //新增表字段
                addTable(){
                    this.ruleForm.table_fields.push({
                        field_name:'',
                        field_display_name:'',
                        searchable:false,
                        type:'string',
                        is_list_display:true,
                        can_search:false,
                        nullable:false,
                        key:'',
                        default:'',
                        rule:'',
                        comment:''
                    });
                },
                //删除表字段
                deleteTable(index){
                    this.ruleForm.table_fields.splice(index,1)
                },
                //添加删除migrate中的表单数据
                addMigrateTable(){
                    this.migrateForm.table_fields.push({
                        field_name:'',
                        field_display_name:'',
                        searchable:false,
                        type:'string',
                        nullable:false,
                        change:false,
                        key:'',
                        default:'',
                        comment:''
                    });
                },
                //删除migrate中的表单数据
                deleteMigrateTable(index){
                    this.migrateForm.table_fields.splice(index,1)
                },
                //提交migrate中的表单数据
                submitMigrateForm(migrateForm) {
                    this.$refs[migrateForm].validate((valid) => {
                        if (valid) {
                            this.loadding=true;
                            axios.post('{{  route('generator.migrate') }}',this.migrateForm).then(function(res){
                                if(res.data.errcode==0){
                                    var data=res.data.data;
                                    var message='';
                                    for(x in data){
                                        message+='<p>'+x+':'+data[x]+"</p><br>";
                                    }
                                    vm.$message({
                                        title: 'success',
                                        dangerouslyUseHTMLString: true,
                                        message: message,
                                        center: true,
                                        type: 'success',
                                        duration:5000
                                    });
                                }else{
                                    vm.$message({
                                        title: 'error',
                                        dangerouslyUseHTMLString: true,
                                        message: res.data.message,
                                        center: true,
                                        type: 'error',
                                        duration:8000
                                    });
                                }
                                vm.loadding=false;
                            });
                        } else {
                            return false;
                        }
                    });
                }
            }
        });

    </script>
@endsection
