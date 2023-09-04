<?php

namespace php\validators;

use php\services\CashierService;
use php\services\Request;

class CheckBlockStatusRequestValidator extends RequestValidator
{
    public static function validate()
    {
        if (!CashierService::ipExists(Request::getClientIp()))
            return abort(403, 'Caixa não encontrado ou indisponível!');
    }
}
