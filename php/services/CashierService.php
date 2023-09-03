<?php

namespace php\services;

use php\models\entities\Cashier;
use php\models\repository\CashierRepository;

class CashierService
{
    public static function list()
    {
        $cashierRepository = new CashierRepository();
        $cashiers = $cashierRepository->getAllById();
    }
}
