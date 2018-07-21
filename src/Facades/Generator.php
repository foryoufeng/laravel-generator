<?php

namespace Foryoufeng\Generator\Facades;

use Illuminate\Support\Facades\Facade;

class Generator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'generator';
    }
}
