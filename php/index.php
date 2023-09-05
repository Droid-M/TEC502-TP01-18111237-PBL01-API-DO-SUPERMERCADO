<?php

// Autoloading to load classes automatically
spl_autoload_register(function ($className) {
    $className = '.' . DIRECTORY_SEPARATOR . (str_starts_with($className, 'php') ? substr($className, 4) : $className);
    $filePath = '.'. DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if (file_exists($filePath)) {
        include $filePath;
    }
});

require_once "./helpers/array_helpers.php";
require_once "./helpers/constants.php";
require_once "./helpers/environment_helpers.php";
require_once "./helpers/output_helpers.php";
require_once "./helpers/response_helper.php";
require_once "./helpers/router_helpers.php";
require_once "./helpers/string_helpers.php";
require_once "./routes/api.php";

use php\services\Database;
use php\services\Response;
use php\services\Route;

Database::init();
Route::treatRequestEndpoint();
try {
    $response = Response::processRequest();
    Response::renderResponse($response);
} catch (Exception|TypeError $e) {
    abort(500, $e->getMessage());
}