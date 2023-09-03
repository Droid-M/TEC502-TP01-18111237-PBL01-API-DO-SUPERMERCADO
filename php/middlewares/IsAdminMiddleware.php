<?php

namespace php\middlewares;

use php\services\Request;
use php\services\Response;

class IsAdminMiddleware extends Middleware
{
    public function run()
    {
        if (Request::getHeaders(ADMIN_TOKEN_KEY) != env(ADMIN_TOKEN_KEY))
            Response::abort('403', 'Restrito somente a administradores!');
    }
}