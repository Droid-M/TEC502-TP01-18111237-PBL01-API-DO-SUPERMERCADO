<?php

namespace php\middlewares;

use php\services\CashierService;
use php\services\Request;
use php\services\Response;

class IsCashier extends Middleware
{
    public function run()
    {
        dd(Request::getHeaders());
        // CashierService::getCashierById()
    }
}