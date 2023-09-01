<?php

require_once "../helpers/string_helpers.php";

function split_route(string $route)
{
    return explode('/', trim(remove_repeated_chars($route), '/'));
}

function parse_route(string $route)
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
