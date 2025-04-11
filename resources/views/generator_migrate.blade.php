<el-tab-pane>
    <span slot="label"><i class="el-icon-document"></i> @lang('laravel-generator::generator.migrate') </span>
    <el-form label-position="top" :model="migrateForm" :rules="rules" ref="migrateForm" label-width="200px" class="demo-ruleForm">
        <el-form-item label="prefix" prop="prefix">
            <el-input v-model="migrateForm.prefix"  style="float: left;width: 400px;margin-right: 20px"></el-input>
        </el-form-item>
        <el-form-item label="tableName" prop="tableName">
            <el-input v-model="migrateForm.tableName"  style="float: left;width: 400px;margin-right: 20px"></el-input><span v-if="migrateForm.tableName && migrateName"><el-tag type="success">@{{migrateName}}</el-tag></span>
        </el-form-item>
        <el-form-item >
            <el-checkbox-group v-model="migrateForm.doMigrate">
                <el-checkbox label="migrate">Run migrate</el-checkbox>
            </el-checkbox-group>
        </el-form-item>
        <el-form-item label="table fileds" prop="delivery">
            <el-row >
                <el-col :span="2">Field name</el-col>

                <el-col :span="3" style="margin-left: 20px">Type</el-col>
                <el-col :span="2">
                    <el-popover placement="top-start" trigger="hover">
                        <p>@lang('laravel-generator::generator.youNeed')<el-tag type="success">$table->decimal('amount', 5, 2)</el-tag>,@lang('laravel-generator::generator.soAttach')</p>
                        <span slot="reference">attach<i class="el-icon-question"></i></span>
                    </el-popover>
                </el-col>
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
                <el-col :span="2">
                    <el-input v-model="table.attach" placeholder="attach"></el-input>
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
                <el-col :span="2" style="margin-left: 10px">
                    <i class="el-icon-delete" style="color: red;cursor: pointer;padding: 5px" @click="deleteMigrateTable(index)"></i>
                </el-col>
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
