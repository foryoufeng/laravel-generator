<?php

namespace Foryoufeng\Generator\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $group
 * @property string $alias
 * @property string $config
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LaravelGeneratorConfig whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LaravelGeneratorConfig extends Model
{

     protected $fillable = ['alias','config','group',];





}
