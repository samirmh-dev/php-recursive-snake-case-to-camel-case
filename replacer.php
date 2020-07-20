<?php

define('SEARCH_AT',__DIR__);

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(SEARCH_AT));

$replacers = [];

foreach ($files as $file) {
    if ($file->isDir()) continue;
  
    $path = $file->getPathname();
  
    $content = file_get_contents($path);

    $regex = '/function [a-zA-Z\d]+(?:_[a-zA-Z\d]+)*/';

    if (!preg_match_all($regex, $content, $functions)) echo "\033[31mWe found no match for $path \033[0m " . PHP_EOL

    foreach ($functions[0] as $function) {
        if(strpos($function, '_') === false) continue;

        $snake_case = str_replace(['function', ' '], '', $function);
        $camelCase = lcfirst(str_replace('_', '', ucwords($snake_case, '_')));

        $content = str_replace($snake_case, $camelCase, $content);

        $replacers[$snake_case] = $camelCase;
    }

    if(!file_put_contents($path, $content)) echo "\033[32mFailed to put new PHP at $path\033[0m " . PHP_EOL;
}
