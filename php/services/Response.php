<?php

namespace php\services;

$currentResponse101031031i220303 = null;
class Response
{
    public static function encodeResponseContent(mixed $content)
    {
        if (is_object($content) && method_exists($content, 'toArray'))
            return json_encode($content->toArray());
        return json_encode((array) $content);
    }

    public static function json(string $status, string|null $message = null, array $content = [], string $headerMessage = null)
    {
        $headerMessage = $headerMessage ?: match ($status) {
            "400" => "Requisição ruim",
            "401" => "Não autenticado",
            "403" => "Não permitido",
            "404" => "Não encontrado",
            "405" => "Método não permitido",
            default => ""
        };
        header("HTTP/1.0 $status $headerMessage");
        return static::encodeResponseContent([
            'message' => $message,
            'data' => $content
        ]);
    }

    public static function abort(string $status, null|string $message = null, array $content = [], string $headerMessage = null)
    {
        echo static::json($status, $message, $content, $headerMessage);
        die;
    }

    public static function getCurrentResponse(): array|null
    {
        global $currentResponse101031031i220303;
        return is_null($currentResponse101031031i220303)
            ? $currentResponse101031031i220303
            : json_decode($currentResponse101031031i220303, true);
    }

    /**
     * Records the response that will be sent back to the client
     * @return void
     */
    public static function register($response)
    {
        global $currentResponse101031031i220303;
        $currentResponse101031031i220303 = is_json($response) ? $response : json_encode($response);
    }

    /**
     * Executes and returns the response from the controller or callback defined in the current request
     * @return mixed
     */
    public static function processRequest()
    {
        $parameters = Request::getPathParameters();
        if (!Request::usesCallback()) {
            $controller = Request::getControllerPath();
            $method = Request::getControllerMethod();
            return (new $controller())->$method(...$parameters);
        } else {
            $callback = Request::getControllerPath();
            return $callback(...$parameters);
        }
    }

    /**
     * Displays the response to the client
     * @param mixed|null $data
     * @return mixed
     */
    public static function renderResponse(mixed $data = null)
    {
        if (!is_json($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
    }
}
