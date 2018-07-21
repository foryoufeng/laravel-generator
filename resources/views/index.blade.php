<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('generator.name') }}</title>

    <!-- Fonts -->
    <link href="/vendor/laravel-generator/css/element.css" rel="stylesheet" type="text/css">

    <!-- Styles -->
</head>
<style>
    .content {
        margin:  0px auto;
    }
   .header {
       text-align: center;
   }
    .footer{
        text-align: center;
    }
    .grid-content {
        border-radius: 4px;
        min-height: 36px;
    }
    .row-bg {
        padding: 10px 0;
        background-color: #f9fafc;
    }
    .header{
        color:#3A88FD;
        padding: 20px;
        font-size: 30px;
    }
    #app input{
    }
    .el-form--label-top .el-form-item__label{
        font-size: 16px;
        font-weight: bold;
    }
</style>
<body>
<div id="app" class="content" v-cloak>
    <div >
        <el-container>

            <el-header class="header">
                <i class="el-icon-rank"></i>{{ config('generator.name') }}
            </el-header>
            <el-main>
                <el-tabs type="border-card">
                    <el-tab-pane>
                        <span slot="label"><i class="el-icon-menu"></i> generator</span>

                        <el-form label-position="top" :model="ruleForm" :rules="rules" ref="ruleForm" label-width="200px" class="demo-ruleForm">
                            <el-form-item label="model" prop="modelName">
                                <el-input v-model="ruleForm.modelName"  style="float: left;width: 400px;margin-right: 20px"></el-input><span v-if="ruleForm.modelName"><el-tag type="success">@{{modelSave}}</el-tag></span>
                            </el-form-item>
                            <el-form-item >
                                <el-checkbox-group v-model="ruleForm.create">
                                    <el-checkbox label="migration">Create migration</el-checkbox>
                                    <el-checkbox label="model">Create model</el-checkbox>
                                    <el-checkbox label="migrate">Run migrate</el-checkbox>
                                    <el-checkbox label="ide-helper">ide-helper:models</el-checkbox>
                                </el-checkbox-group>
                            </el-form-item>
                            @foreach($checkLists as $checkList)
                            <el-form-item label="{{ $checkList['name'] }}">
                                <el-checkbox-group v-model="ruleForm.{{ $checkList['name'] }}">
                                    <el-checkbox v-for="item in all_{{ $checkList['name'] }}" :label="item" :key="item">@{{item}}<span v-if="ruleForm.modelName">@{{ruleForm.modelName}}{{ $checkList['postfix'] }}</span></el-checkbox>
                                </el-checkbox-group>
                            </el-form-item>
                            @endforeach
                            <el-form-item :label="file.name" v-for="(file,index) in ruleForm.single">
                                    <el-checkbox v-model="file.isChecked" style="margin-left: 20px" @change="handleCheck(file,index)">@{{ file.namespace }}<span v-if="ruleForm.modelName">@{{ruleForm.modelName}}@{{file.postfix}}</span></el-checkbox>
                            </el-form-item>
                            <el-form-item label="unit test">
                                <el-checkbox v-model="ruleForm.unittest" style="margin-left: 20px">\Tests\Unit\<span v-if="ruleForm.modelName">@{{ruleForm.modelName}}Test</span></el-checkbox>
                            </el-form-item>
                            <el-form-item label="table fileds" prop="delivery">
                                <el-row >
                                    <el-col :span="2">Field name</el-col>
                                    {{--<el-col :span="3" style="margin-left: 20px">Display name</el-col>--}}
                                    {{--<el-col :span="1">Searchable</el-col>--}}
                                    <el-col :span="3" style="margin-left: 20px">Type</el-col>
                                    <el-col :span="1">Nullable</el-col>
                                    <el-col :span="3">Key</el-col>
                                    <el-col :span="3" style="margin-left: 20px">Default value</el-col>
                                    <el-col :span="3" style="margin-left: 20px">Comment</el-col>
                                    <el-col :span="2" style="margin-left: 10px">Action</el-col>
                                </el-row>
                                <el-row v-for="(table,index) in ruleForm.table_fields" style="margin-bottom: 20px">
                                    <el-col :span="2">
                                        <el-input v-model="table.field_name" placeholder="field name"></el-input>
                                    </el-col>
                                    {{--<el-col :span="3" style="margin-left: 20px">--}}
                                        {{--<el-input v-model="table.display_name" placeholder="display name"></el-input>--}}
                                    {{--</el-col>--}}
                                    {{--<el-col :span="1">--}}
                                    {{--<el-checkbox v-model="table.searchable" style="margin-left: 20px"></el-checkbox>--}}
                                    {{--</el-col>--}}
                                    <el-col :span="3" style="margin-left: 20px">
                                        <el-select v-model="table.type" placeholder="please select"  filterable >
                                            <el-option
                                                    v-for="item in dbTypes"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                            </el-option>
                                        </el-select>
                                    </el-col>
                                    <el-col :span="1">
                                        <el-checkbox v-model="table.nullable" style="margin-left: 20px"></el-checkbox>
                                    </el-col>
                                    <el-col :span="3">
                                        <el-select v-model="table.key" placeholder="please select">
                                            <el-option
                                                    v-for="item in keys"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                            </el-option>
                                        </el-select>
                                    </el-col>
                                    <el-col :span="3" style="margin-left: 20px">
                                        <el-input v-model="table.default" placeholder="default value"></el-input>
                                    </el-col>
                                    <el-col :span="3" style="margin-left: 20px">
                                        <el-input v-model="table.comment" placeholder="comment"></el-input>
                                    </el-col>
                                    <el-col :span="2" style="margin-left: 10px"><el-button type="danger" icon="el-icon-delete"  @click="deleteTable(index)">remove</el-button></el-col>
                                </el-row>
                            </el-form-item>
                            <el-form-item >
                                <el-button type="success" @click="addTable" icon="el-icon-plus" style="float: left;">Add field</el-button>
                                <span style="float: left;margin-left:100px;">Primary key</span><el-input v-model="ruleForm.primary_key"  style="float: left;width: 200px;margin-right: 50px"></el-input>
                                <el-switch
                                        v-model="ruleForm.timestamps"
                                        active-text="Created_at & Updated_at"
                                >
                                </el-switch>
                                <el-switch
                                        v-model="ruleForm.soft_deletes"
                                        active-text="Soft deletes"
                                >
                                </el-switch>
                            </el-form-item>

                            <el-form-item>
                                <el-button type="primary" @click="submitForm('ruleForm')" :loading="loadding">submit</el-button>
                            </el-form-item>
                        </el-form>
                    </el-tab-pane>
                    <el-tab-pane>
                        <span slot="label"><i class="el-icon-document"></i> migrate </span>
                        <el-form label-position="top" :model="migrateForm" :rules="rules" ref="migrateForm" label-width="200px" class="demo-ruleForm">
                            <el-form-item label="prefix" prop="prefix">
                                <el-input v-model="migrateForm.prefix"  style="float: left;width: 400px;margin-right: 20px"></el-input>
                            </el-form-item>
                            <el-form-item label="tableName" prop="tableName">
                                <el-input v-model="migrateForm.tableName"  style="float: left;width: 400px;margin-right: 20px"></el-input><span v-if="migrateForm.tableName && migrateName"><el-tag type="success">@{{migrateName}}</el-tag></span>
                            </el-form-item>
                            <el-form-item >
                                <el-checkbox-group v-model="migrateForm.doMigrate">
                                    {{--<el-checkbox label="migration">Create migration</el-checkbox>--}}
                                    <el-checkbox label="migrate">Run migrate</el-checkbox>
                                </el-checkbox-group>
                            </el-form-item>
                            <el-form-item label="table fileds" prop="delivery">
                                <el-row >
                                    <el-col :span="2">Field name</el-col>

                                    <el-col :span="3" style="margin-left: 20px">Type</el-col>
                                    <el-col :span="1">Nullable</el-col>
                                    <el-col :span="3">Key</el-col>
                                    <el-col :span="3" style="margin-left: 20px">Default value</el-col>
                                    <el-col :span="3" style="margin-left: 20px">Comment</el-col>
                                    <el-col :span="1">Change</el-col>
                                    <el-col :span="2" style="margin-left: 10px">Action</el-col>
                                </el-row>
                                <el-row v-for="(table,index) in migrateForm.table_fields" style="margin-bottom: 20px">
                                    <el-col :span="2">
                                        <el-input v-model="table.field_name" placeholder="field name"></el-input>
                                    </el-col>
                                    <el-col :span="3" style="margin-left: 20px">
                                        <el-select v-model="table.type" placeholder="please select"  filterable >
                                            <el-option
                                                    v-for="item in dbTypes"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                            </el-option>
                                        </el-select>
                                    </el-col>
                                    <el-col :span="1">
                                        <el-checkbox v-model="table.nullable" style="margin-left: 20px"></el-checkbox>
                                    </el-col>
                                    <el-col :span="3">
                                        <el-select v-model="table.key" placeholder="please select">
                                            <el-option
                                                    v-for="item in keys"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                            </el-option>
                                        </el-select>
                                    </el-col>
                                    <el-col :span="3" style="margin-left: 20px">
                                        <el-input v-model="table.default" placeholder="default value"></el-input>
                                    </el-col>
                                    <el-col :span="3" style="margin-left: 20px">
                                        <el-input v-model="table.comment" placeholder="comment"></el-input>
                                    </el-col>
                                    <el-col :span="1">
                                        <el-checkbox v-model="table.change" style="margin-left: 20px"></el-checkbox>
                                    </el-col>
                                    <el-col :span="2" style="margin-left: 10px"><el-button type="danger" icon="el-icon-delete"  @click="deleteMigrateTable(index)">remove</el-button></el-col>
                                </el-row>
                            </el-form-item>
                            <el-form-item >
                                <el-button type="success" @click="addMigrateTable" icon="el-icon-plus" style="float: left;">Add field</el-button>
                            </el-form-item>

                            <el-form-item>
                                <el-button type="primary" @click="submitMigrateForm('migrateForm')" :loading="loadding">submit</el-button>
                            </el-form-item>
                        </el-form>
                    </el-tab-pane>
                </el-tabs>

            </el-main>
            <el-footer class="footer"> ©{{ config('generator.name') }}</el-footer>
        </el-container>
</div>
</div>
<script src="/vendor/laravel-generator/js/vue.js"></script>
<script src="/vendor/laravel-generator/js/axios.js"></script>
<script src="/vendor/laravel-generator/js/element-2.4.js"></script>
<script>
    const vm=new Vue({
        el: '#app',
        data:{
            loadding:false,
            dbTypes:@json($dbTypes),
            keys: [{
                value: '',
                label: 'NULL'
            }, {
                value: 'unique',
                label: 'Unique'
            }, {
                value: 'index',
                label: 'Index'
            }],
            @foreach($all_checkLists as $all_checkList)
            '{{ $all_checkList['name'] }}':@json($all_checkList['value']),
            @endforeach
            ruleForm: {
                modelName: '',
                create:[
                    'migration',
                    'model',
                   'migrate',
                    'ide-helper'
                ],
                primary_key:'id',
                timestamps:true,
                table_fields:[{
                    field_name:'',
                    _display_name:'',
                    type:'string',
                    nullable:false,
                    key:'',
                    default:'',
                    comment:''
                    }],
                single:@json(config('generator.single')),
                soft_deletes:false,
                unittest:true,
                @foreach($checkLists as $checkList)
                '{{ $checkList['name'] }}':@json($checkList['value']),
                @endforeach
            },
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
                    comment:''
                }],
            },
            rules: {
                prefix: [
                    { required: true, message: 'prefix is required', trigger: 'blur' },
                ],
                modelName: [
                    { required: true, message: 'model is required', trigger: 'blur' },
                ],
                tableName: [
                    { required: true, message: 'table name is required', trigger: 'blur' },
                ],
            }
        },
        computed: {
             modelSave: function () {
                 if(this.ruleForm.modelName){
                     this.ruleForm.modelName=this.ruleForm.modelName[0].toUpperCase()+this.ruleForm.modelName.substring(1)
                 }
                 return @json($generator['modelPath'])+this.ruleForm.modelName;
             },
            migrateName:function () {
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
            }
        },
        mounted(){

        },
        methods:{
            handleCheck(item,index){
                Vue.set(this.ruleForm.single,index,item)
            },
            submitForm(formName) {
                console.log(this.ruleForm);
                //return;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.loadding=true;
                        axios.post('{{  URL::current() }}',this.ruleForm).then(function(res){
                            if(res.data.code==200){
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
                                vm.$message.error(res.data.message);
                            }
                            vm.loadding=false;
                        });
                    } else {
                        return false;
                    }
                });
            },
            addTable(){
                this.ruleForm.table_fields.push({
                    field_name:'',
                    _display_name:'',
                    searchable:false,
                    type:'string',
                    nullable:false,
                    key:'',
                    default:'',
                    comment:''
                });
            },
            deleteTable(index){
                this.ruleForm.table_fields.splice(index,1)
            },
            addMigrateTable(){
                this.migrateForm.table_fields.push({
                    field_name:'',
                    _display_name:'',
                    searchable:false,
                    type:'string',
                    nullable:false,
                    key:'',
                    default:'',
                    comment:''
                });
            },
            deleteMigrateTable(index){
                this.migrateForm.table_fields.splice(index,1)
            },
            submitMigrateForm(migrateForm) {
                console.log(this.migrateForm);
                this.$refs[migrateForm].validate((valid) => {
                    if (valid) {
                        this.loadding=true;
                        axios.post('{{  URL::current() }}',this.migrateForm).then(function(res){
                            if(res.data.code==200){
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
                                vm.$message.error(res.data.message);
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
</body>
</html>
