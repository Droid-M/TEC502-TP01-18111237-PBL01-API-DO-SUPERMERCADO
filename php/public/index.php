<?php

// Autoloading para carregar classes automaticamente
spl_autoload_register(function ($className) {
    $filePath = __DIR__ . '/../../' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if (file_exists($filePath)) {
        include $filePath;
    }
});

require_once "../helpers/output_helpers.php";
require_once "../helpers/router_helpers.php";
require_once "../routes/api.php";

use php\services\ResponseService;
use php\services\RouteService;

RouteService::treatRequestEndpoint();