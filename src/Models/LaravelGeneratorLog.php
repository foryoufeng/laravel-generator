<?php

namespace Foryoufeng\Generator\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class LaravelGeneratorLog extends Model
{

    protected $guarded=[];
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
