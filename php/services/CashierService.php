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

    public function 
}
