<?php

namespace php\middlewares;

use php\services\Request;
use php\services\Response;

class IsCashier extends Middleware
{
    public function run()
    {
        if (Request::getHeaders(CASHIER_TOKEN_KEY) != env(CASHIER_TOKEN_KEY))
            abort(403, 'Restrito somente a caixistas!');
    }
}