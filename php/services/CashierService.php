<?php

namespace php\services;

use php\models\entities\Cashier;
use php\models\repository\CashierRepository;
use php\models\repository\ProductRepository;

class CashierService
{
    public static function list()
    {
        $cashierRepository = new ProductRepository();
        return $cashierRepository->getAllById();
    }
}
