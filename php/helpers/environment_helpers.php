<?php

$envFilePath = __DIR__ . '/../.env';

/**
 * @param string $key
 * @return mixed|string|null
 */
function env(string $key)
{
    global $envFilePath;
    if (file_exists($envFilePath)) {
        $envVariables = parse_ini_file($envFilePath, false, INI_SCANNER_RAW);
        return array_get($envVariables, $key);
    } else {
        dd("O arquivo $envFilePath não foi encontrado.");
    }
}
