<?php

namespace php\validators;

use php\services\ProductService;
use php\services\PurchaseService;
use php\services\Request;
use php\validators\RequestValidator;

class PayPurchaseRequestValidator extends RequestValidator
{
    public static function validate()
    {
        if (is_null($purchase = PurchaseService::getById(Request::getPathParameters('id'))))
            abort(404, 'Compra não encontrada!');
        if ($purchase->status == 'paid')
            abort(403, 'A compra selecionada já foi paga anteriormente!');
        else if ($purchase->status != 'started')
            abort(403, 'A compra selecionada não pode ser paga!');
        $paymentMtd = Request::getInputParameters('payment_method');
        if ($paymentMtd != 'pix' && $paymentMtd != 'credit_card' && $paymentMtd != 'cash')
            abort(422, 'Dados inválidos!', ['payment_method' => "O valor deve ser 'pix', 'cash' ou 'credit_card'"]);
    }
}
