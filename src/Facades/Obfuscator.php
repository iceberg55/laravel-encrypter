<?php

namespace ibf\LaravelEncrypter\Facades;

use ibf\LaravelEncrypter\LaravelObfuscator;
use Illuminate\Support\Facades\Facade;

class Obfuscator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LaravelObfuscator::class;
    }
}