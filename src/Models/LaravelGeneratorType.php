<?php

namespace Foryoufeng\Generator\Models;

use Illuminate\Database\Eloquent\Model;

class LaravelGeneratorType extends Model
{

    protected $guarded=[];

    public const MODEL='Model';
    public const Controllers='Controllers';
    public const Views='Views';
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates()
    {
        return $this->hasMany(LaravelGenerator::class,'template_id','id');
    }
}
