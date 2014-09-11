<?php namespace Oven\Recipe;

use Illuminate\Support\Str;

class Parser {

    /**
     * Generate path from argument
     *
     * @param string $argument
     * @return string
     */
    public static function getPath($argument)
    {
        $directories = explode('/', $argument);

        $directories = array_map(function ($dir) {
            return Str::title($dir);
        }, $directories);

        return implode('/', $directories);
    }

}
