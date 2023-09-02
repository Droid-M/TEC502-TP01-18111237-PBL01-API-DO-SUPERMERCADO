<?php

namespace php\controllers;

use CashierService;

class CashierController
{
    public function info()
    {
        return json(200, 'Informações sobre caixas consultada com sucesso!', CashierService::list());
    }
}