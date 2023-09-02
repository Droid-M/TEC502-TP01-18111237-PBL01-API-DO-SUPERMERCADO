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
}
