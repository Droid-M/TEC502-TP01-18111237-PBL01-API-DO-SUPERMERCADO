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

use php\models\repository\ProductRepository;
use php\services\Response;
use php\services\Route;

Route::treatRequestEndpoint();

$dsn = 'mysql:host=localhost;dbname=smart_supermarket';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    dd([(new ProductRepository($pdo))->getById(1), [["34" => "nu"], ["ab" => "cd"]]]);
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}