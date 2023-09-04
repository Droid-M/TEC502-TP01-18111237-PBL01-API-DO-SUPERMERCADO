<?php

namespace php\services;

use Exception;
use php\models\repository\CashierRepository;

class CashierService
{
    public static function getCashierById(string $id)
    {
        return (new CashierRepository())->getCashierInfoByColumn('id', $id);
    }

    public static function getCashierByIp(string $ip)
    {
        return (new CashierRepository())->getCashierInfoByColumn('ip', $ip);
    }

    public static function list()
    {
        return (new CashierRepository())->listCashiersInfo();
    }

    public static function register(string $ip, bool $isBlocked = false)
    {
        return (new CashierRepository())->save(['ip' => $ip, 'is_blocked' => $isBlocked]);
    }

    public static function blockOrRelease(string $id, bool $block)
    {
        return (new CashierRepository())->updateStatus($id, $block);
    }

    public static function checkIfIsBlockedById(int $cashierId)
    {
        return (new CashierRepository())->getById($cashierId, ['is_blocked'])['is_blocked'];
    }

    public static function checkIfIsBlockedByIp(string $cashierIp)
    {
        return array_get((new CashierRepository())->getByColumn('ip', $cashierIp, ['is_blocked']), 'is_blocked');
    }

    public static function idExists(string $id)
    {
        return null != (new CashierRepository())->getByColumn('id', $id, ['id']);
    }

    public static function ipExists(string $ip)
    {
        return null != (new CashierRepository())->getByColumn('ip', $ip, ['id']);
    }
}
