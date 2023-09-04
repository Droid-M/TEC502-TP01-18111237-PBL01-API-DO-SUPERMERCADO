<?php

namespace php\controllers;

use Exception;
use php\services\Database;
use php\services\PurchaseService;
use php\services\Request;
use phps\validators\RegisterPurchaseRequestValidator;

class PurchaseController
{
    public function register()
    {
        RegisterPurchaseRequestValidator::validate();
        return Database::transaction(function () {
            return json(
                201,
                'Dados da compra registrados com sucesso!',
                [
                    'purchase' => PurchaseService::registerNewPurchase(
                        Request::getClientIp(),
                        Request::getInputParameters('products_bar_code')
                    )->toArray()
                ]
            );
        });
    }
}
