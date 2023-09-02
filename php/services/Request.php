<?php

namespace php\services;

use php\middlewares\Middleware;

$request = [];

class Request
{
    public static function getClientIp()
    {
        $clientIP = $_SERVER['REMOTE_ADDR'];
        if (filter_var($clientIP, FILTER_VALIDATE_IP)) {
            return $clientIP;
        } else {
            return null; // Endereço IP inválido
        }
    }

    public static function registerRequest(array $newRequest)
    {
        global $request;
        $request = $newRequest;
        $request["query_parameters"] = $_GET;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request["input_parameters"] = $_POST;
        } else {
            $newRequest["input_parameters"] = json_decode(file_get_contents("php://input"), 1);
        }

        $request["headers"] = $_SERVER;
    }
    // if (!$selectedRoute["is_callback"]) {
    //     $controller = $selectedRoute["controller_path"];
    //     $method = $selectedRoute["controller_method"];
    //     return (new $controller())->$method(...$parameters);
    // } else {
    //     $callback = $selectedRoute["callback"];
    //     return $callback(...$parameters);
    // }

    public static function getPath(): string
    {
        global $request;
        return $request["path"];
    }

    public static function getControllerPath()
    {
        global $request;
        return $request["controller_path"];
    }

    public static function getControllerMethod()
    {
        global $request;
        return $request["controller_method"];
    }

    public static function getPathParameters(string|null $key = null): array|null|string
    {
        global $request;
        return is_null($key) ? $request["path_parameters"] : array_get($request["path_parameters"], $key);
    }

    public static function getInputParameters(string|null $key = null): array|null|string
    {
        global $request;
        return is_null($key) ? $request["input_parameters"] : array_get($request["input_parameters"], $key);
    }

    public static function getQueryParameters(string|null $key = null): array|null|string
    {
        global $request;
        return is_null($key) ? $request["query_parameters"] : array_get($request["query_parameters"], $key);
    }

    public static function getHeaders(string|null $key = null): array|null|string
    {
        global $request;
        if (is_null($key))
            return $request["headers"];
        $key = !str_starts_with($key, "HTTP_") ? 'HTTP_' . strtoupper($key) : strtoupper($key);
        return array_get($request["headers"], $key);
    }

    public static function getMiddlewares(string|null $key = null): array|null|string|Middleware
    {
        global $request;
        return is_null($key) ? $request["middlewares"] : array_get($request["middlewares"], $key);
    }
}
