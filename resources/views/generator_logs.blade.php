<el-tab-pane name="log">
    <span slot="label"><i class="el-icon-menu"></i> @lang('laravel-generator::generator.generateLog')</span>

    <el-form ref="form"  :inline="true">
        <el-form-item label="{{ trans('laravel-generator::generator.modelName') }}:">
            <el-input v-model="logSearch.model_name" @keyup.enter.native="getLogs()" style="float: left;width: 150px"></el-input>
        </el-form-item>
        <el-form-item label="{{ trans('laravel-generator::generator.displayName') }}:">
            <el-input v-model="logSearch.display_name" @keyup.enter.native="getLogs()" style="float: left;width: 150px"></el-input>
        </el-form-item>
        <el-form-item  label="{{ trans('laravel-generator::generator.creator') }}:">
            <el-input v-model="logSearch.creator" @keyup.enter.native="getLogs()" style="float: left;width: 150px"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="getLogs()" icon="el-icon-search" style="float: left;margin-left: 10px"></el-button>
        </el-form-item>
        <el-form-item>
            <el-button type="danger" style="float: left;margin-left: 10px" @click="switchTab('generator',getRuleForm())">{{ trans('laravel-generator::generator.add') }}</el-button>
        </el-form-item>
    </el-form>
    <el-table
            :data="logs"
            stripe
            border
            style="width: 100%">
        <el-table-column
            label="ID"
            prop="id"
        >
        </el-table-column>
        <el-table-column
                label="@lang('laravel-generator::generator.modelName')"
                >
            <template slot-scope="scope">
                <el-button  @click="logDetail(scope.row)" type="text">@{{ scope.row.model_name }}</el-button>
            </template>
        </el-table-column>
        <el-table-column
                prop="display_name"
                label="@lang('laravel-generator::generator.displayName')"
        >
        </el-table-column>
        <el-table-column
                prop="creator"
                label="@lang('laravel-generator::generator.creator')">
        </el-table-column>
        <el-table-column
            prop="created_at"
            label="@lang('laravel-generator::generator.addTime')">
        </el-table-column>
        <el-table-column
            prop="updated_at"
            label="@lang('laravel-generator::generator.updateTime')">
        </el-table-column>
        <el-table-column
                fixed="right"
                label="{{ trans('laravel-generator::generator.actions') }}"
                width="200">
            <template slot-scope="scope">

                <el-tooltip class="item" effect="dark" content="@lang('laravel-generator::generator.edit')" placement="bottom-end">
                    <el-button type="primary" @click="editLog(scope.row,scope.row.id)" icon="el-icon-edit" circle></el-button>
                </el-tooltip>
                <el-tooltip class="item" effect="dark" content="@lang('laravel-generator::generator.copy')" placement="bottom-end">
                    <el-button  @click="editLog(scope.row,0)" type="warning" icon="el-icon-document" circle></el-button>
                </el-tooltip>
                <el-tooltip class="item" effect="dark" content="@lang('laravel-generator::generator.delete')" placement="bottom-end">
                    <el-button  @click="deleteLog(scope.row.id)" type="danger" icon="el-icon-delete" circle></el-button>
                </el-tooltip>
            </template>
        </el-table-column>

    </el-table>
    <div style="margin-top: 20px; display: flex; align-items: center; justify-content: center; gap: 10px;">
        <span>@lang('laravel-generator::generator.total') @{{ pageInfo.total }} @lang('laravel-generator::generator.line')</span>
        <el-pagination
            @current-change="handlePage"
            :current-page.sync="pageInfo.current_page"
            :page-size="pageInfo.per_page"
            layout="prev, pager, next"
            :total="pageInfo.total">
        </el-pagination>
    </div>
</el-tab-pane>
<el-dialog title="" center :visible.sync="dialogTableVisible">
    <p>
        <h2>
            <span>@lang('laravel-generator::generator.modelName'):</span>
            <span style="margin-right: 40px">@{{ logRow.modelName }}</span>
            @lang('laravel-generator::generator.displayName'): @{{ logRow.modelDisplayName }}
        </h2>
    </p>
    <p>
        <el-checkbox-group v-if="logRow &&logRow.create" v-model="logRow.create">
            <el-checkbox label="migration">Create migration</el-checkbox>
            <el-checkbox label="migrate" >Run migrate</el-checkbox>
            <el-checkbox label="ide-helper" >ide-helper:models</el-checkbox>
        </el-checkbox-group>
    </p>
    <p>
        <h2>Table fileds</h2>
    </p>
    <el-table :data="logRow.table_fields" border style="width: 100%">
        <el-table-column prop="field_name" label="field_name" width="150"></el-table-column>
        <el-table-column prop="field_display_name" label="@lang('laravel-generator::generator.displayName')" width="150"></el-table-column>
        <el-table-column prop="type" label="Type"></el-table-column>
        <el-table-column prop="attach" label="attach"></el-table-column>
        <el-table-column  label="Nullable">
            <template slot-scope="scope">
                <el-checkbox v-model="scope.row.nullable"></el-checkbox>
            </template>
        </el-table-column>
        <el-table-column prop="key" label="Key"></el-table-column>
        <el-table-column width="150" prop="default" label="Default Value"></el-table-column>
        <el-table-column width="150" prop="comment" label="Comment"></el-table-column>
        <el-table-column  label="@lang('laravel-generator::generator.showLists')">
            <template slot-scope="scope">
                <el-checkbox v-model="scope.row.is_show_lists"></el-checkbox>
            </template>
        </el-table-column>
        <el-table-column  label="@lang('laravel-generator::generator.canSearch')">
            <template slot-scope="scope">
                <el-checkbox v-model="scope.row.can_search"></el-checkbox>
            </template>
        </el-table-column>
        <el-table-column prop="rule" label="@lang('laravel-generator::generator.rule')"></el-table-column>
    </el-table>
    <p>
        <el-switch style="user-select: none;"
                   v-model="logRow.timestamps"
                   active-text="Created_at & Updated_at"
        >
        </el-switch>
        <el-switch style="margin-left: 20px;user-select: none; "
                   v-model="logRow.soft_deletes"
                   active-text="Soft deletes"
        >
        </el-switch>

    </p>
{{--    <p>--}}
{{--        <h2>@lang('laravel-generator::generator.foreign')</h2>--}}
{{--    </p>--}}
</el-dialog>
