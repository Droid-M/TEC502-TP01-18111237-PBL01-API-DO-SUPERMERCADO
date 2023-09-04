<?php

namespace php\middlewares;

use php\services\CashierService;
use php\services\Request;
use php\services\Response;

class IsCashierMiddleware extends Middleware
{
    public function run()
    {
        if (Request::getHeaders(CASHIER_TOKEN_KEY) != env(CASHIER_TOKEN_KEY) || CashierService::getCashierByIp(Request::getClientIp()) == null)
            abort(403, 'Restrito somente a caixistas!');
    }
}
