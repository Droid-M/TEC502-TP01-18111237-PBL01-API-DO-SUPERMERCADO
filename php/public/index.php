<?php

// Autoloading to load classes automatically
spl_autoload_register(function ($className) {
    $filePath = __DIR__ . '/../../' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if (file_exists($filePath)) {
        include $filePath;
    }
});

require_once "../helpers/array_helpers.php";
require_once "../helpers/constants.php";
require_once "../helpers/environment_helpers.php";
require_once "../helpers/output_helpers.php";
require_once "../helpers/response_helper.php";
require_once "../helpers/router_helpers.php";
require_once "../helpers/string_helpers.php";
require_once "../routes/api.php";

use php\services\Database;
use php\services\Request;
use php\services\Response;
use php\services\Route;

Database::init();
Route::treatRequestEndpoint();
$response = Response::processRequest();
Response::renderResponse($response);