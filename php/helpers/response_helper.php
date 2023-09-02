<?php

use php\services\Response;

function json(string $status, string|null $message = null, array $content = [], string $headerMessage = null)
{
    return Response::json($status, $message, $content, $headerMessage);
}

function abort(string $status, null|string $message = null, array $content = [], string $headerMessage = null)
{
    return Response::abort($status, $message, $content, $headerMessage);
}

function is_json($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
