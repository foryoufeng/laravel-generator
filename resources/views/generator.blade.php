<el-tab-pane>
    <span slot="label"><i class="el-icon-menu"></i> generator</span>
    <el-form label-position="top" :model="ruleForm"  status-icon :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
        <el-form-item label="model" prop="modelName" style="margin-bottom: 5px">
            <el-input v-model="ruleForm.modelName"  style="float: left;width: 400px;margin-right: 20px"></el-input>
        </el-form-item>
        <el-form-item label="@lang('laravel-generator::generator.displayName')" prop="modelDisplayName" style="margin-bottom: 5px">
            <el-popover placement="top-start" trigger="hover" >
                <p>@lang('laravel-generator::generator.modelDisplayNameDesc')</p>
                <span slot="reference"><i class="el-icon-question"></i></span>
            </el-popover>
            <el-input v-model="ruleForm.modelDisplayName"  style="float: left;width: 400px;margin-right: 20px"></el-input>
        </el-form-item>
        <el-form-item >
            <el-checkbox-group v-model="ruleForm.create">
                <el-checkbox label="migration">Create migration</el-checkbox>
                <el-checkbox label="migrate">Run migrate</el-checkbox>
                <el-checkbox label="ide-helper">ide-helper:models</el-checkbox>
                <el-checkbox label="unittest">\Tests\Unit\<span v-if="ruleForm.modelName">@{{ruleForm.modelName}}Test</span></el-checkbox>
            </el-checkbox-group>
        </el-form-item>
        {{--   模板的数据     --}}
        <el-form-item :label="item.name" v-for="item in template_types">
            <el-checkbox-group v-model="ruleForm.templates[''+item.name+'']">
                <el-checkbox v-for="template in item.templates" :label="template.id" :key="template.id">@{{template.file_real_name}}</el-checkbox>
            </el-checkbox-group>
        </el-form-item>
        {{--   表字段/start    --}}
        <el-form-item label="table fileds">
            <el-row >
                <el-col :span="2">
                    <el-popover placement="top-start" trigger="hover" >
                        <p>@lang('laravel-generator::generator.fieldName')</p>
                        <span slot="reference">Field name<i class="el-icon-question"></i></span>
                    </el-popover>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-popover placement="top-start" trigger="hover">
                        <p>@lang('laravel-generator::generator.displayNameDesc')</p>
                        <span slot="reference">@lang('laravel-generator::generator.displayName')<i class="el-icon-question"></i></span>
                    </el-popover>
                </el-col>
                <el-col :span="2"style="margin-left: 10px">Type</el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-popover placement="top-start" trigger="hover">
                        <p>@lang('laravel-generator::generator.youNeed')<el-tag type="success">$table->decimal('amount', 5, 2)</el-tag>,@lang('laravel-generator::generator.soAttach')</p>
                        <span slot="reference">attach<i class="el-icon-question"></i></span>
                    </el-popover>
                </el-col>
                <el-col :span="1">Nullable</el-col>
                <el-col :span="2" style="margin-left: 10px">Key</el-col>
                <el-col :span="3" style="margin-left: 20px">Default Value</el-col>
                <el-col :span="2" style="margin-left: 20px">Comment</el-col>
                <el-col :span="1" style="width: 80px">
                    <el-popover placement="top-start" trigger="hover">
                        <p>@lang('laravel-generator::generator.showListsDesc')</p>
                        <span slot="reference">@lang('laravel-generator::generator.showLists')<i class="el-icon-question"></i></span>
                    </el-popover>
                </el-col>
                <el-col :span="1" style="width: 80px">
                    <el-popover placement="top-start" trigger="hover">
                        <p>@lang('laravel-generator::generator.canSearchDesc')</p>
                        <span slot="reference">@lang('laravel-generator::generator.canSearch')<i class="el-icon-question"></i></span>
                    </el-popover>
                </el-col>
                <el-col :span="1">Rule</el-col>
                <el-col :span="1">Action</el-col>
            </el-row>
            <el-row v-for="(table,index) in ruleForm.table_fields" style="margin-bottom: 20px">
                <el-col :span="2">
                    <el-input v-model="table.field_name" placeholder="field name"></el-input>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-input v-model="table.display_name" placeholder="@lang('laravel-generator::generator.displayName')"></el-input>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-select v-model="table.type" placeholder="please select"  filterable >
                        <el-option
                                v-for="item in dbTypes"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-input v-model="table.attach" placeholder="attach"></el-input>
                </el-col>
                <el-col :span="1" style="margin-left: 10px">
                    <el-checkbox v-model="table.nullable"></el-checkbox>
                </el-col>
                <el-col :span="2">
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
                <el-col :span="2" style="margin-left: 20px">
                    <el-input v-model="table.comment" placeholder="comment"></el-input>
                </el-col>
                <el-col :span="1" style="margin-left: 10px">
                    <el-checkbox v-model="table.is_show_lists"></el-checkbox>
                </el-col>
                <el-col :span="1">
                    <el-checkbox v-model="table.can_search"></el-checkbox>
                </el-col>
                <el-col :span="1">
                    <el-select v-model="table.rule"
                               filterable
                               clearable
                               placeholder="rule">
                        <el-option
                                v-for="item in fieldRules"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="1" style="margin-left: 10px"><el-button type="danger" icon="el-icon-delete"  @click="deleteTable(index)">remove</el-button></el-col>
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
        {{--   表字段/end    --}}

        {{--    添加外键关系/start    --}}
        <el-form-item>
            <el-button type="danger" @click="addForeign" icon="el-icon-plus">@lang('laravel-generator::generator.add') @lang('laravel-generator::generator.foreign')</el-button>
        </el-form-item>
        <el-form-item v-if="isShowForeign">
            <el-row >
                <el-col :span="2">
                    foreign
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    references
                </el-col>
                <el-col :span="4" style="margin-left: 10px">on</el-col>
                <el-col :span="3" style="margin-left: 10px">
                    onDelete
                </el-col>
                <el-col :span="3" style="margin-left: 10px">
                    onUpdate
                </el-col>
                <el-col :span="1">Action</el-col>
            </el-row>
            <el-row v-for="(foreign,index) in ruleForm.foreigns" style="margin-bottom: 20px">
                <el-col :span="2">
                    <el-select v-model="foreign.foreign" placeholder="foreign"
                               no-data-text="@lang('laravel-generator::generator.noData')">
                        <el-option
                                v-for="item in foreignFileds"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"

                        >
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-select v-model="foreign.references" placeholder="references"
                               no-data-text="@lang('laravel-generator::generator.noData')">
                        <el-option
                                v-for="item in referencesFileds[index]"
                                :key="item"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="4" style="margin-left: 10px">
                    <el-select v-model="foreign.on" @change="onForeignChange(index)" placeholder="on"
                               filterable
                               no-data-text="@lang('laravel-generator::generator.noData')">
                        <el-option
                                v-for="item in tables"
                                :key="item.name"
                                :label="item.name"
                                :value="item.name">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="3">
                    <el-select v-model="foreign.onUpdate" clearable placeholder="please select">
                        <el-option
                                v-for="item in onDeleteUpdate"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="3">
                    <el-select v-model="foreign.onDelete" clearable placeholder="please select">
                        <el-option
                                v-for="item in onDeleteUpdate"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="2" style="margin-left: 10px"><el-button type="danger" icon="el-icon-delete"  @click="deleteForeign(index)">remove</el-button></el-col>
            </el-row>
        </el-form-item>
        {{--    添加外键关系/end    --}}

        {{--    添加关联关系/start    --}}
        <el-form-item>
            <el-button type="warning" icon="el-icon-star-off"  @click="addRelationship">@lang('laravel-generator::generator.add') @lang('laravel-generator::generator.relationship')</el-button>
        </el-form-item>
        <el-form-item v-if="isShowRelationship">
            <el-row >
                <el-col :span="2">relationship</el-col>
                <el-col :span="2" style="margin-left: 10px">
                    RelationModel
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    foreign_key
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    Reverse
                </el-col>
                <el-col :span="1" style="margin-left: 10px">
                    With
                </el-col>
                <el-col :span="1" style="margin-left: 10px">
                    Search
                </el-col>
                <el-col :span="1">Action</el-col>
            </el-row>
            <el-row v-for="(relationship,index) in ruleForm.relationships" style="margin-bottom: 20px">
                <el-col :span="2">
                    <el-select v-model="relationship.relationship" placeholder="relationship"
                               no-data-text="@lang('laravel-generator::generator.noData')">
                        <el-option
                                v-for="item in relationships"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                        <el-input v-model="relationship.model"  placeholder="Model" ></el-input>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-input v-model="relationship.foreign_key"  placeholder="foreign_key" ></el-input>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-select v-model="relationship.reverse" placeholder="reverse"
                               clearable
                               no-data-text="@lang('laravel-generator::generator.noData')">
                        <el-option
                                v-for="item in relationships"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col :span="1" style="margin-left: 10px">
                    <el-checkbox v-model="relationship.with">with</el-checkbox>
                </el-col>
                <el-col :span="1">
                    <el-checkbox v-model="relationship.can_search">search</el-checkbox>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-button type="danger" icon="el-icon-delete"  @click="deleteRelationship(index)">remove</el-button>
                </el-col>
                <el-col :span="2" style="margin-left: 10px">
                    <el-tag type="danger" v-if="relationship.relation">@{{ relationship.relation }}</el-tag>
                    <el-tag type="danger" v-if="relationship.reverseRelation">@{{ relationship.reverseRelation }}</el-tag>
                </el-col>
            </el-row>
        </el-form-item>
        {{--    添加关联关系/end     --}}

        <el-form-item>
            <el-button type="primary" @click="submitForm('ruleForm')" :loading="loadding">submit</el-button>
        </el-form-item>

    </el-form>
</el-tab-pane>