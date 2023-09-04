<?php

namespace php\middlewares;

use php\services\CashierService;
use php\services\Request;
use php\services\Response;

class IsUnlockedCashierMiddleware extends Middleware
{
    public function run()
    {
        if (CashierService::getCashierByIp(Request::getClientIp())->is_blocked == true)
            abort(403, 'Caixa bloqueado para compras!');
    }
}
