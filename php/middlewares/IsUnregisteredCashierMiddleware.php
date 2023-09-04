<?php

namespace php\middlewares;

use php\services\CashierService;
use php\services\Request;

class IsUnregisteredCashierMiddleware extends Middleware
{
    public function run()
    {
        if (Request::getHeaders(CASHIER_TOKEN_KEY) != env(CASHIER_TOKEN_KEY))
            abort(403, 'Restrito somente a caixistas!');
        if (CashierService::getCashierByIp(Request::getClientIp()) != null)
            abort(403, 'Rota disponível apenas para caixas ainda não registrados!');
    }
}
