<?php

function split_endpoint(string $route)
{
    return explode('/', trim(remove_repeated_chars($route), '/'));
}

function tidy_endpoint(string $path)
{
    return trim(remove_repeated_chars($path));
}

function parse_endpoint(string $route)
{
    $paths = explode('/', $route);
    do {
        $path = array_shift($paths);
    } while ($path == "" && sizeof($paths));
    $parsedPathsPointer = [$path => [
        "value" => $path,
        "children" => null
    ]];
    if (sizeof($paths)) {
        $parsedPathsPointer[$path]["children"] = [];
        $parsedPaths = &$parsedPathsPointer[$path]["children"];
        foreach ($paths as $path) {
            if ($path) {
                $parsedPaths["value"] = $path;
                $parsedPaths["children"] = [
                    "value" => $path,
                    "children" => null
                ];
                $parsedPaths = &$parsedPaths["children"];
            }
        }
    }
    return $parsedPathsPointer;
}
