<?php

namespace php\services;

define("GET_METHOD", "GET");
define("POST_METHOD", "POST");
define("PUT_METHOD", "PUT");
define("DELETE_METHOD", "DELETE");

use Closure;
use php\middlewares\Middleware;
use php\services\Request;

$routes0209292309s239s2k2k = [];

class Route
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

    protected static function runMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            if ($middleware instanceof Middleware) {
                $middleware->run();
            } else {
                (new $middleware())->run();
            }
        }
    }

    public static function treatRequestEndpoint()
    {
        global $routes0209292309s239s2k2k;
        $requestPath = static::requestPath();
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $filteredRoutes = [];
        foreach ($routes0209292309s239s2k2k as $route) {
            $definedUri = trim(remove_repeated_chars($route["endpoint"]), '/');
            if (static::uri_match($requestPath, $definedUri)) {
                $filteredRoutes[$route["method"]] = $route;
            }
        }
        if (!sizeof($filteredRoutes))
            Response::abort(404, "Endpoint '$requestPath' não encontrado!");
        $selectedRoute = $filteredRoutes[$requestMethod] ?? null;
        if (null == $selectedRoute)
            Response::abort(405);
        static::runMiddlewares($selectedRoute["middlewares"]);
        $selectedRoute["path"] = $requestPath;
        $selectedRoute["path_parameters"] = static::get_path_parameters($requestPath, trim(remove_repeated_chars($selectedRoute["endpoint"]), '/'));
        Request::registerRequest($selectedRoute);
        // ResponseService::abort(404, "Endpoint '$requestPath' não encontrado!");
    }

    /* ---------------------- Captura informações de rotas ---------------------- */
    public static function requestPath()
    {
        return trim(remove_repeated_chars(explode('?', $_SERVER['REQUEST_URI'])[0]), '/');
    }
}

class RouteRegister
{
    protected string|Closure $controllerPathOrCallback;
    protected string|null $controllerMethod;
    protected string $endpoint;
    protected array $middlewares = [];

    public function __construct(string $endpoint, string|Closure $controllerPathOrCallback, string|null $controllerMethod = null)
    {
        $this->endpoint = $endpoint;
        $this->controllerPathOrCallback = $controllerPathOrCallback;
        $this->controllerMethod = $controllerMethod;

        return $this;
    }

    protected function registerEndpoint(string $requestMethod)
    {
        global $routes0209292309s239s2k2k;
        if (is_string($this->controllerPathOrCallback)) {
            $routes0209292309s239s2k2k[$requestMethod . $this->endpoint] = [
                "method" => $requestMethod,
                "endpoint" => $this->endpoint,
                "controller_path" => $this->controllerPathOrCallback,
                "controller_method" => $this->controllerMethod,
                "callback" => null,
                "is_callback" => false,
                "middlewares" => $this->middlewares
            ];
        } else {
            $routes0209292309s239s2k2k[$requestMethod . $this->endpoint] = [
                "method" => $requestMethod,
                "endpoint" => $this->endpoint,
                "controller_path" => null,
                "controller_method" => null,
                "callback" => $this->controllerPathOrCallback,
                "is_callback" => true,
                "middlewares" => $this->middlewares
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

    public function middleware(Middleware|string $middleware)
    {
        $this->middlewares[] = $middleware;
        return $this;
    }
}
