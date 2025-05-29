<?php

/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 12/26/18
 * Time: 7:30 PM.
 */

namespace Foryoufeng\Generator\Controllers;

use Foryoufeng\Generator\GeneratorUtils;
use Foryoufeng\Generator\Message;
use Foryoufeng\Generator\Models\LaravelGenerator;
use Foryoufeng\Generator\Models\LaravelGeneratorType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * Class GeneratorTemplateController.
 */
class GeneratorTemplateController extends BaseController
{
    use Message;

    /**
     * 返回ajax列表数据.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $name = $request->get('name');
        $template_id = $request->get('template_id');
        $query = LaravelGenerator::with('template_type');
        if ($name) {
            $query = $query->where('name', 'like', '%'.$name.'%');
        }
        if ($template_id) {
            $query = $query->where('template_id', $template_id);
        }

        return $this->success($query->get());
    }

    /**
     * 更新操作.
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, ?string $locale = null)
    {
        $locale = $locale ?? config('app.locale', 'en');
        if (! in_array($locale, ['en', 'zh_CN'])) {
            $locale = 'en';
        }
        App::setLocale($locale);
        // do ajax request
        $id = (int) $request->get('id');
        // 表单数据
        $form = $this->getForm($id);
        // 获取模板组列表
        $template_types = $this->getTemplateTypes();
        // 提供的演示数据
        $laravel_generators = GeneratorUtils::getGenerators();
        // 可用的假属性字段
        $dummyAttrs = GeneratorUtils::getDummyAttrs();
        // 可用的函数
        $functions = GeneratorUtils::getFunctions();
        // 自定义变量
        $customKeys = GeneratorUtils::getCustomKeys();
        $tags = GeneratorUtils::getTags();
        $language_value = $locale === 'en' ? 'English' : '简体中文';

        return view('laravel-generator::template_update', compact('template_types', 'tags', 'locale', 'language_value',
            'laravel_generators', 'dummyAttrs', 'functions', 'form', 'customKeys'));
    }

    /**
     * 删除操作.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $id = (int) $request->get('id');
        $generator = LaravelGenerator::with('template_type')->find($id);
        if ($generator) {
            if ($generator->template_type->name == LaravelGeneratorType::MODEL) {
                return $this->error(trans('laravel-generator::generator.modelNotDelete'));
            }
            if ($generator->delete()) {
                $count = LaravelGenerator::where('template_id', $generator->template_id)->count();
                //
                if ($count == 0) {
                    LaravelGeneratorType::whereId($generator->template_id)->delete();
                }

                return $this->success(trans('laravel-generator::generator.deleteSuccess'));
            }
        }

        return $this->error(trans('laravel-generator::generator.deleteFailed'));
    }

    public function updateType(Request $request)
    {
        $name = $request->get('name');
        $id = $request->get('id');
        if ($id > 0) {
            $generator_type = LaravelGeneratorType::whereId($id)->first();
        } else {
            $generator_type = new LaravelGeneratorType;
        }
        $generator_type->name = $name;
        $generator_type->save();

        return $this->success($generator_type->toArray());
    }

    /**
     * 保存数据.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int',
            'name' => 'required',
            'template_id' => [
                'required',
                'integer',
                Rule::exists('laravel_generator_types', 'id'),
            ],
            'is_checked' => 'required|boolean',
            'path' => 'required',
            'file_name' => 'required',
            'template' => 'required',
        ]);
        if (! $data['id']) {
            $generator = new LaravelGenerator;
        } else {
            $generator = LaravelGenerator::findOrFail($data['id']);
        }
        $generator->fill($data);

        if ($generator->save()) {
            return $this->success(trans('laravel-generator::generator.submitSuccess'));
        }

        return $this->error(trans('laravel-generator::generator.submitError'));
    }

    /**
     * 获取模板列表.
     *
     * @return LaravelGeneratorType[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    private function getTemplateTypes()
    {
        return LaravelGeneratorType::all()->map(function ($item) {
            $data = [];
            $data['label'] = $item->name;
            $data['value'] = $item->id;

            return $data;
        });
    }

    /**
     * 获取模板数据.
     *
     *
     * @return array
     */
    private function getForm($id)
    {
        $form = [
            'id' => 0,
            'is_checked' => true,
            'template_id' => '',
            'template' => '',
            'path' => '',
            'file_name' => '',
        ];

        $generator = LaravelGenerator::find($id);
        if ($generator) {
            $form = $generator->toArray();
        }

        return $form;
    }

    public function compile(Request $request)
    {
        $template = $request->get('template');
        if(!$template){
            return $this->error(trans('laravel-generator::generator.template_not_empty'));
        }
        try {
            $result = GeneratorUtils::demo_compile($template);
            return $this->success(['template'=>$result]);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }
}
