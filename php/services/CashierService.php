<?php

namespace php\services;

use php\models\entities\Cashier;
use php\models\repository\CashierRepository;
use php\models\repository\PurchaseRepository;

class CashierService
{
    public static function list()
    {
        $cashierRepository = new CashierRepository();
        $purchaseRepository = new PurchaseRepository();
    }
}
