<?php namespace Oven;

use Illuminate\Support\Str;

class Parser {

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

    public static function extract($source, $argument)
    {
        preg_match('/[(]+[A-Za-z]+[)]/', $source, $match);
        $method = preg_replace('/[()]/', '', $match[0]);

        $name = forward_static_call(['self', $method], $argument);

        return str_replace($match[0], $name, $source);
    }

}
