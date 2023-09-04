<?php

namespace php\validators;

use php\services\PurchaseService;
use php\services\Request;
use php\validators\RequestValidator;

class CancelPurchaseRequestValidator extends RequestValidator
{
    public static function validate()
    {
        if (is_null($purchase = PurchaseService::getById(Request::getPathParameters('id'))))
            abort(404, 'Compra não encontrada!');
        if ($purchase->status != 'started')
            abort(403, 'A compra selecionada não pode ser cancelada!');
    }
}
