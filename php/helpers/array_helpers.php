<?php

function array_get(array $array, string $key, mixed $default = null)
{
    $keys = explode('.', $key);
    foreach ($keys as $k) {
        if (is_array($array) && array_key_exists($k, $array)) {
            $array = $array[$k];
        } else {
            return $default;
        }
    }
    return $array;
}

/**
 * Adapted from @link https://stackoverflow.com/a/43004994
 * @return array|mixed
 */
function array_map_assoc(callable $f, array $a)
{
    return array_replace_recursive(...array_map($f, array_keys($a), $a));
}

/**
 * @param array $haystack
 * @param array $keyMap ['old_key' => 'new_key']
 * @return array
 */
function array_replace_keys($haystack, $keyMap)
{
    $arrayC = [];

    foreach ($haystack as $key => $value) {
        if (isset($keyMap[$key])) {
            $newKey = $keyMap[$key];
            $arrayC[$newKey] = $value;
        } else {
            $arrayC[$key] = $value;
        }
    }

    return $arrayC;
}

function array_only(array $inputArray, array $allowedKeys)
{
    return array_intersect_key($inputArray, array_flip($allowedKeys));
}

function array_except(array $inputArray, array $keysToExclude)
{
    foreach ($keysToExclude as $key) {
        unset($inputArray[$key]);
    }
    return $inputArray;
}

function array_first_value(array $array, mixed $default = null)
{
    $key = array_key_first($array);
    return is_null($key)
        ? $default
        : $array[$key] ?? $default;
}
