<?php

namespace php\controllers;

use Exception;
use php\services\CashierService;
use php\services\Database;
use php\services\PurchaseService;
use php\services\Request;
use php\validators\CancelPurchaseRequestValidator;
use php\validators\PayPurchaseRequestValidator;
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
        } catch (Exception | TypeError $e) {
            abort(500, $e->getMessage());
        }
    }

    public function pay()
    {
        PayPurchaseRequestValidator::validate();
        return Database::transaction(function () {
            return json(
                200,
                'Pagamento registrado com sucesso!',
                PurchaseService::payPurchase(
                    Request::getPathParameters('id'),
                    Request::getInputParameters('payment_method'),
                    Request::getInputParameters('purchaser_name'),
                    Request::getInputParameters('purchaser_cpf')
                )->toArray()
            );
        });
    }
    
    public function cancel()
    {
        CancelPurchaseRequestValidator::validate();
        return Database::transaction(function () {
            return json(
                200,
                'Compra cancelada com sucesso!!',
                PurchaseService::cancelPurchase(Request::getPathParameters('id'))->toArray()
            );
        });
        
    }
}
