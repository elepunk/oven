<?php namespace Oven;

use Illuminate\Support\Str;

class ArgumentReader {

    public static function entity($argument)
    {
        $names = explode('/', $argument);

        return Str::title(array_pop($names));
    }

    public static function path($argument)
    {
        $names = explode('/', $argument);
        $names = array_map(function($name) {
            return Str::title($name);
        }, $names);

        return implode('/', $names);
    }

}
