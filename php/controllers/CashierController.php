<?php

namespace php\controllers;

use php\services\CashierService;

class CashierController
{
    public function list()
    {
        return json(200, 'Informações sobre caixas consultada com sucesso!', CashierService::list());
    }
}