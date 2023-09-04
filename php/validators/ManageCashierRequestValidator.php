<?php

namespace php\validators;

use php\services\CashierService;
use php\services\Request;

class ManageCashierRequestValidator extends RequestValidator
{
    public static function validate()
    {
        $status = Request::getInputParameters('status');
        $id = Request::getPathParameters('id');
        if (is_null($status))
            return abort(422, 'Dados inválidos!', ['status' => 'Campo é necessário!']);
        else if ($status != 'block' && $status != 'release')
            return abort(422, 'Dados inválidos!', ['status' => 'Apenas "block" ou "release" é permitido!']);
        if (is_null(CashierService::getCashierById($id)))
            return abort(403, 'Caixa não encontrado ou indisponível!');
    }
}
