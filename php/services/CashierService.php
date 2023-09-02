<?php

use php\models\repository\CashierRepository;

$cashierRepository922939s399222 = new CashierRepository();
class CashierService
{
    public static function list()
    {
        global $cashierRepository922939s399222;
        return $cashierRepository922939s399222->getAllById();
    }
}
