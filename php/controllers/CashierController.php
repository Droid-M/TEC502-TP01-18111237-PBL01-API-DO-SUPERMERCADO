<?php

namespace php\controllers;

use php\models\entities\Cashier;
use php\services\CashierService;
use php\services\Request;

class CashierController
{
    public function list()
    {
        return json(200, 'Informações sobre caixas consultada com sucesso!', CashierService::list()->toArray());
    }

    public function register()
    {
        if (!CashierService::register(Request::getClientIp()))
            return json(400, "Falha ao registrar caixa no sistema!");
        return json(201, "Caixa registrado com sucesso!", CashierService::getCashierByIp(Request::getClientIp())->toArray());
    }
}