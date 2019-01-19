<?php

namespace Foryoufeng\Generator\Models;

use Illuminate\Database\Eloquent\Model;

class LaravelGenerator extends Model
{
    protected $guarded=[];

    /**
     * 应该被转化为原生类型的属性
     *
     * @var array
     */
    protected $casts = [
        'is_checked' => 'boolean',
    ];

    /**
     * 设置路径
     *
     * @param $value
     */
    public function setPathAttribute($value)
    {
        $this->attributes['path'] = trim($value,'/').'/';
    }

    /**
     * 设置文件名
     *
     * @param $value
     */
    public function setFileNameAttribute($value)
    {
        $this->attributes['file_name'] = trim($value,'/');
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template_type()
    {
        return $this->belongsTo(LaravelGeneratorType::class,'template_id','id');
    }

    /**
     * 设置是否选中
     *
     * @param $value
     */
    public function setIsCheckedAttribute($value)
    {
        $this->attributes['is_checked'] = (int)$value;
    }
}
