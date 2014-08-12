<?php namespace Oven;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Parser {

    public static function entity($argument)
    {
        $names = explode('/', $argument);

        return Str::title(array_pop($names));
    }

    public static function namespaces($argument)
    {
        $names = explode('/', $argument);
        $names = array_map(function($name) {
            return Str::title($name);
        }, $names);

        return implode('\\', $names);
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
        preg_match('/[(]+[A-Za-z]+[)]/', $source, $matches);
        $method = preg_replace('/[()]/', '', $matches[0]);

        $name = forward_static_call(['self', $method], $argument);

        return str_replace($matches[0], $name, $source);
    }

    public static function buildSource($raw, $argument)
    {
        preg_match_all('/[(]+[A-Za-z]+[)]/', $raw, $matches);
        
        $matches = Arr::flatten($matches);

        foreach ($matches as $match) {
            $method = preg_replace('/[()]/', '', $match);
            $name = forward_static_call(['self', $method], $argument);

            $raw = str_replace($match, $name, $raw);
        }

        return $raw;
    }

}
