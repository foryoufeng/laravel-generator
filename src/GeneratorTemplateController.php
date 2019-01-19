<?php
/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 12/26/18
 * Time: 7:30 PM.
 */

namespace Foryoufeng\Generator;

use Illuminate\Http\Request;
use Foryoufeng\Generator\Models\LaravelGenerator;
use Illuminate\Routing\Controller as BaseController;
use Foryoufeng\Generator\Models\LaravelGeneratorType;

/**
 * Class GeneratorTemplateController.
 */
class GeneratorTemplateController extends BaseController
{
    use Message;

    /**
     * 返回ajax列表数据.
     *
     * @param Request $request
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
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        //do ajax request
        $id = (int) $request->get('id');
        //表单数据
        $form = $this->getForm($id);
        //获取模板组列表
        $template_types = $this->getTemplateTypes();
        //提供的演示数据
        $laravel_generators = GeneratorUtils::getGenerators();
        //可用的假属性字段
        $dummyAttrs = GeneratorUtils::getDummyAttrs();
        //可用的函数
        $functions = GeneratorUtils::getFunctions();

        return view('laravel-generator::template_update', compact('template_types', 'laravel_generators', 'dummyAttrs', 'functions', 'form'));
    }

    /**
     * 删除操作.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $id = (int) $request->get('id');
        $generator = LaravelGenerator::with('template_type')->find($id);
        if ($generator) {
            if (LaravelGeneratorType::MODEL == $generator->template_type->name) {
                return $this->error(trans('laravel-generator::generator.modelNotDelete'));
            }
            if ($generator->delete()) {
                $count = LaravelGenerator::where('template_id', $generator->template_id)->count();
                //
                if (0 == $count) {
                    LaravelGeneratorType::whereId($generator->template_id)->delete();
                }

                return $this->success(trans('laravel-generator::generator.deleteSuccess'));
            }
        }

        return $this->error(trans('laravel-generator::generator.deleteFailed'));
    }

    /**
     * 保存数据.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int',
            'name' => 'required',
            'template_id' => 'required',
            'is_checked' => 'required|boolean',
            'path' => 'required',
            'file_name' => 'required',
            'template' => 'required',
        ]);
        $template_id = $data['template_id'];
        $generator_type = LaravelGeneratorType::find($template_id);
        if (!$generator_type) {
            $generator_type = LaravelGeneratorType::firstOrCreate([
                'name' => $template_id,
            ]);
            $data['template_id'] = $generator_type->id;
        }
        if (!$data['id']) {
            $generator = new LaravelGenerator();
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
        return  LaravelGeneratorType::all()->map(function ($item) {
            $data = [];
            $data['label'] = $item->name;
            $data['value'] = $item->id;

            return $data;
        });
    }

    /**
     * 获取模板数据.
     *
     * @param $id
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
}
