<?php

namespace php\services;

use Closure;
use php\middlewares\Middleware;

$request102392039s2k20202 = [];

class Request
{
    public static function getClientIp()
    {
        $clientIP = $_SERVER['REMOTE_ADDR'];
        if (filter_var($clientIP, FILTER_VALIDATE_IP)) {
            return $clientIP;
        } else {
            return null; // Invalid IP
        }
    }

    public static function getRequestData()
    {
        global $request102392039s2k20202;
        return $request102392039s2k20202;
    }

    public static function registerRequest(array $newRequest)
    {
        global $request102392039s2k20202;
        $request102392039s2k20202 = $newRequest;
        $request102392039s2k20202["query_parameters"] = $_GET;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request102392039s2k20202["input_parameters"] = $_POST;
        } else {
            $newRequest["input_parameters"] = json_decode(file_get_contents("php://input"), 1);
        }
        $request102392039s2k20202["headers"] = $_SERVER;
    }

    public static function getPath(): string
    {
        global $request102392039s2k20202;
        return $request102392039s2k20202["path"];
    }

    public static function getControllerPath()
    {
        global $request102392039s2k20202;
        return $request102392039s2k20202["controller_path"];
    }

    public static function getControllerMethod()
    {
        global $request102392039s2k20202;
        return $request102392039s2k20202["controller_method"];
    }

    public static function getPathParameters(string|null $key = null): array|null|string
    {
        global $request102392039s2k20202;
        return is_null($key) ? $request102392039s2k20202["path_parameters"] : array_get($request102392039s2k20202["path_parameters"], $key);
    }

    public static function getInputParameters(string|null $key = null): array|null|string
    {
        global $request102392039s2k20202;
        return is_null($key) ? $request102392039s2k20202["input_parameters"] : array_get($request102392039s2k20202["input_parameters"], $key);
    }

    public static function getQueryParameters(string|null $key = null): array|null|string
    {
        global $request102392039s2k20202;
        return is_null($key) ? $request102392039s2k20202["query_parameters"] : array_get($request102392039s2k20202["query_parameters"], $key);
    }

    public static function getHeaders(string|null $key = null): array|null|string
    {
        global $request102392039s2k20202;
        if (is_null($key))
            return $request102392039s2k20202["headers"];
        $key = !str_starts_with($key, "HTTP_") ? 'HTTP_' . strtoupper($key) : strtoupper($key);
        return array_get($request102392039s2k20202["headers"], $key);
    }

    public static function getMiddlewares(string|null $key = null): array|null|string|Middleware
    {
        global $request102392039s2k20202;
        return is_null($key) ? $request102392039s2k20202["middlewares"] : array_get($request102392039s2k20202["middlewares"], $key);
    }

    public static function usesCallback(): bool
    {
        global $request102392039s2k20202;
        return $request102392039s2k20202["is_callback"];
    }

    public static function getCallback(): Closure
    {
        global $request102392039s2k20202;
        return $request102392039s2k20202["callback"];
    }
}
