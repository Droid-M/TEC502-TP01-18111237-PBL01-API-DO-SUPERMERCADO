<?php

namespace php\services;

class ResponseService
{
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
        header("HTTP/1.0 404 $headerMessage");
        $content["message"] = $message;
        return json_encode($content);
    }

    public static function abort(string $status, null|string $message = null, array $content = [], string $headerMessage = null)
    {
        echo static::json($status, $message, $content, $headerMessage);
        die;
    }
}
