<?php

namespace php\controllers;

use Exception;
use php\models\entities\Cashier;
use php\services\CashierService;
use php\services\Database;
use php\services\Request;
use php\validators\ManageCashierRequestValidator;

class CashierController
{
    public function list()
    {
        return json(200, 'Informações sobre caixas consultada com sucesso!', CashierService::list()->toArray());
    }

    public function register()
    {
        try {
            return Database::transaction(function () {
                return json(201, "Caixa registrado com sucesso!", CashierService::getCashierByIp(Request::getClientIp())->toArray());
            });
        } catch (Exception $e) {
            return json(400, "Falha ao registrar caixa no sistema!");
        }
    }

    public function manage()
    {
        ManageCashierRequestValidator::validate();
        try {
            return Database::transaction(function () {
                $status = Request::getInputParameters('status');
                $cashierId = Request::getPathParameters('id');
                if ($status == 'block') {
                    CashierService::blockOrRelease($cashierId, true);
                    return json(200, 'Caixa bloqueado com sucesso!', CashierService::getCashierById($cashierId)->toArray());
                } // else:
                CashierService::blockOrRelease($cashierId, false);
                return json(200, 'Caixa desbloqueado com sucesso!', CashierService::getCashierById($cashierId)->toArray());
            });
        } catch (Exception $e) {
            return json(400, 'Falha ao alterar o bloqueio do caixa!');
        }
    }

    public function checkStatus()
    {
        return json(200, 'Status retornado com sucesso!', CashierService::checkIfIsBlocked(Request::getPathParameters('id')));
    }
}
