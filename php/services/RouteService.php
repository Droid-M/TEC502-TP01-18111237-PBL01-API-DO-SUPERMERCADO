<?php

namespace php\services;

require_once "../helpers/output_helpers.php";
require_once "../helpers/router_helpers.php";
require_once "../helpers/string_helpers.php";

define("GET_METHOD", "GET");
define("POST_METHOD", "POST");
define("PUT_METHOD", "PUT");
define("DELETE_METHOD", "DELETE");

use Closure;

$routes = [];

class RouteService
{
    /* ------------------------------------ Registram rotas ----------------------------------- */

    public static function register(string $endpoint, string|Closure $controllerPathOrCallback, string|null $controllerMethod = null)
    {
        return new RouteRegister($endpoint, $controllerPathOrCallback, $controllerMethod);
    }

    /* ------------------------------ Validam rotas ----------------------------- */

    protected static function get_path_parameters(string $requestUri, string $definedUri)
    {
        $parameters = [];
        $requestUri = split_route($requestUri);
        $definedUri = split_route($definedUri);
        $uriSize = sizeof($requestUri);
        if ($uriSize != sizeof($definedUri))
            return null;
        for ($i = 0; $i < $uriSize; $i++) {
            if (preg_match('/^[a-zA-Z0-9]*\{[a-zA-Z0-9]*\}$/', $definedUri[$i]))
                $parameters[] = $requestUri[$i];
        }
        return $parameters;
    }

    protected static function uri_match(string $requestUri, string $definedUri)
    {
        // if (strlen($definedUri) != strlen($requestUri))
        //     return false;
        $requestUri = split_route($requestUri);
        $definedUri = split_route($definedUri);
        $uriSize = sizeof($requestUri);
        if ($uriSize != sizeof($definedUri))
            return false;
        for ($i = 0; $i < $uriSize; $i++) {
            if ($requestUri[$i] != $definedUri[$i] && !preg_match('/^[a-zA-Z0-9]*\{[a-zA-Z0-9]*\}$/', $definedUri[$i])) {
                return false;
            }
        }
        return true;
    }


    public static function treatRequestEndpoint()
    {
        global $routes;
        $requestUri = trim(remove_repeated_chars(static::uri()), '/');
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $filteredRoutes = [];
        foreach ($routes as $route) {
            $definedUri = trim(remove_repeated_chars($route["endpoint"]), '/');
            if (static::uri_match($requestUri, $definedUri)) {
                $filteredRoutes[$route["method"]] = $route;
            }
        }
        if (!sizeof($filteredRoutes))
            ResponseService::abort(404, "Endpoint '$requestUri' não encontrado!");
        $selectedRoute = $filteredRoutes[$requestMethod] ?? null;
        if (null == $selectedRoute)
            ResponseService::abort(405);
        $parameters = static::get_path_parameters($requestUri, trim(remove_repeated_chars($selectedRoute["endpoint"]), '/'));
        echo json_encode($selectedRoute);
        die;
        if (!$selectedRoute["is_callback"]) {
            $controller = $selectedRoute["controller_path"];
            $method = $selectedRoute["controller_method"];
            return (new $controller())->$method(...$parameters);
        } else {
            $callback = $selectedRoute["callback"];
            return $callback(...$parameters);
        }
        ResponseService::abort(404, "Endpoint '$requestUri' não encontrado!");
    }

    /* ---------------------- Captura informações de rotas ---------------------- */
    public static function uri()
    {
        return explode('?', $_SERVER['REQUEST_URI'])[0];
    }
}

class RouteRegister
{
    protected string|Closure $controllerPathOrCallback;
    protected string|null $controllerMethod;
    protected string $endpoint;

    public function __construct(string $endpoint, string|Closure $controllerPathOrCallback, string|null $controllerMethod = null)
    {
        $this->endpoint = $endpoint;
        $this->controllerPathOrCallback = $controllerPathOrCallback;
        $this->controllerMethod = $controllerMethod;

        return $this;
    }

    protected function registerEndpoint(string $requestMethod)
    {
        global $routes;
        if (is_string($this->controllerPathOrCallback)) {
            $routes[$this->endpoint] = [
                "method" => $requestMethod,
                "endpoint" => $this->endpoint,
                "controller_path" => $this->controllerPathOrCallback,
                "controller_method" => $this->controllerMethod,
                "callback" => null,
                "is_callback" => false
            ];
        } else {
            $routes[$this->endpoint] = [
                "method" => $requestMethod,
                "endpoint" => $this->endpoint,
                "controller_path" => null,
                "controller_method" => null,
                "callback" => $this->controllerPathOrCallback,
                "is_callback" => true
            ];
        }
    }

    public function get()
    {
        return static::registerEndpoint(GET_METHOD);
    }

    public function post()
    {
        return static::registerEndpoint(POST_METHOD);
    }

    public function put()
    {
        return static::registerEndpoint(PUT_METHOD);
    }

    public function delete()
    {
        return static::registerEndpoint(DELETE_METHOD);
    }
}
