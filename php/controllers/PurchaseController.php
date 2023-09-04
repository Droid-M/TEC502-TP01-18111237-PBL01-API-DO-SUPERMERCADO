<?php

namespace php\controllers;

use Exception;
use php\services\CashierService;
use php\services\Database;
use php\services\PurchaseService;
use php\services\Request;
use php\validators\RegisterPurchaseRequestValidator;
use TypeError;

class PurchaseController
{
    public function register()
    {
        RegisterPurchaseRequestValidator::validate();
        try {
            return Database::transaction(function () {
                $cashier = CashierService::getCashierByIp(Request::getClientIp());
                return json(
                    201,
                    'Dados da compra registrados com sucesso!',
                    [
                        'purchase' => PurchaseService::registerNewPurchase(
                            $cashier->id,
                            Request::getInputParameters('products_bar_code')
                        )->toArray()
                    ]
                );
            });
        } catch (Exception|TypeError $e) {
            abort(500, $e->getMessage());
        }
    }

    public function pay()
    {
        
    }
}
