<?php

namespace php\services;

use php\models\repository\ProductRepository;
use php\models\repository\PurchaseRepository;

class PurchaseService
{
    public static function register()
    {
        return (new PurchaseRepository())->;
    }
}
