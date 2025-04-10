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
            style="width: 100%">
        <el-table-column
            label="ID"
            prop="id"
        >
        </el-table-column>
        <el-table-column
                label="@lang('laravel-generator::generator.modelName')"
                prop="model_name"
                >
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
    <div style="margin-top: 20px; text-align: center;">
        <el-pagination
            @current-change="handlePage"
            :current-page.sync="pageInfo.current_page"
            :page-size="pageInfo.per_page"
            layout="total, prev, pager, next"
            :total="pageInfo.total">
        </el-pagination>
    </div>
</el-tab-pane>
