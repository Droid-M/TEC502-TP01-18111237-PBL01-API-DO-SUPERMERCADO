<?php

namespace php\services;

use php\models\repository\CashierRepository;

class CashierService
{
    public static function list()
    {
        $cashierRepository = new CashierRepository(); //FIXME - - 
        return $cashierRepository->getAllById();
    }
}
