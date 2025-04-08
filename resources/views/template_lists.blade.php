<el-tab-pane name="templates">
    <span slot="label"><i class="el-icon-star-on"></i> {{ trans('laravel-generator::generator.templates') }} </span>

    <el-form ref="form" >
        <el-form-item label="{{ trans('laravel-generator::generator.templateName') }}:">
            <el-input v-model="search.name" @keyup.enter.native="getData()" style="float: left;width: 150px"></el-input>
            <el-select v-model="search.template_id"
                       filterable
                       clearable
                       placeholder="{{ trans('laravel-generator::generator.group') }}"  style="float: left;margin-left: 10px;">
                <el-option
                        v-for="item in {{ $template_types['select']}}"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                </el-option>
            </el-select>
            <el-button type="primary" @click="getData()" icon="el-icon-search" style="float: left;margin-left: 10px"></el-button>
            <a href="{{ route('generator.template.update') }}" target="_blank">
                <el-button type="danger" icon="el-icon-plus" style="float: left;margin-left: 10px">{{ trans('laravel-generator::generator.add') }}</el-button>
            </a>
        </el-form-item>
    </el-form>
    <el-table
            :data="templates"
            stripe
            style="width: 100%">
        <el-table-column
                label="{{ trans('laravel-generator::generator.templateName') }}"
                prop="name"
                >
        </el-table-column>
        <el-table-column
                prop="path"
                label="{{ trans('laravel-generator::generator.templatePath') }}"
        >
        </el-table-column>
        <el-table-column
                prop="file_name"
                label="{{ trans('laravel-generator::generator.templateFileName') }}">
        </el-table-column>
        <el-table-column
                prop="is_checked"
                label="{{ trans('laravel-generator::generator.templateIsChecked') }}">
            <template slot-scope="scope">
                <el-tag :type="scope.row.is_checked==true?'success':'danger'">
                    <i :class="scope.row.is_checked?'el-icon-success':'el-icon-error'"></i>
                </el-tag>
            </template>
        </el-table-column>
        <el-table-column
                prop="template_type.name"
                label="{{ trans('laravel-generator::generator.group') }}">
        </el-table-column>
        <el-table-column
                fixed="right"
                label="{{ trans('laravel-generator::generator.actions') }}"
                width="200">
            <template slot-scope="scope">
                <a :href="'{{ route('generator.template.update') }}?id='+scope.row.id" target="_blank">
                    <el-button type="primary" icon="el-icon-edit" circle></el-button>
                </a>
                <el-button v-if="'Model'!=scope.row.template_type.name" @click="deleteTemplate(scope.row.id)" type="danger" icon="el-icon-delete" circle></el-button>
            </template>
        </el-table-column>
    </el-table>

</el-tab-pane>
