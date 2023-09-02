<?php

namespace php\services;

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

    public static function getPathParameters(): array
    {
        global $request;
        return $request["path_parameters"];
    }

    public static function getInputParameters(): array
    {
        global $request;
        return $request["input_parameters"];
    }

    public static function getQueryParameters(): array|null|string
    {
        global $request;
        return $request["query_parameters"];
    }

    public static function getHeaders(): array
    {
        global $request;
        return $request["headers"];
    }

    public static function getMiddlewares()
    {
        global $request;
        return $request["middlewares"];
    }
}
