<?php

namespace php\controllers;

use Exception;
use php\services\Database;
use php\services\Request;
use phps\validators\RegisterPurchaseRequestValidator;

class PurchaseController
{
    public function register()
    {
        RegisterPurchaseRequestValidator::validate();
        return Database::transaction(function () {
            
        });
    }
}
