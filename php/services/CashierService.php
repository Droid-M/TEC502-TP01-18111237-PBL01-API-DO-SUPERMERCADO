<?php

namespace php\services;

use php\models\repository\CashierRepository;
use php\models\repository\ProductRepository;

$cashierRepository922939s399222 = new ProductRepository();
class CashierService
{
    public static function list()
    {
        global $cashierRepository922939s399222;
        return $cashierRepository922939s399222->getAllById();
    }
}
