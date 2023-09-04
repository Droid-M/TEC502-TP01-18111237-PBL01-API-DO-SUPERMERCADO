<?php

use php\controllers\CashierController;
use php\middlewares\IsAdminMiddleware;
use php\middlewares\IsUnregisteredCashierMiddleware;
use php\middlewares\IsCashierMiddleware;
use php\services\Route;

/* ----------------------------- Cashier Routes ----------------------------- */

Route::register("api/cashiers/register", CashierController::class, "register")
    ->middleware(IsUnregisteredCashierMiddleware::class)
    ->post();


/* ------------------------- Admin Routes ------------------------- */

Route::register("api/cashiers/{id}/manage", CashierController::class, "manage")
    ->middleware(IsAdminMiddleware::class)
    ->post();
Route::register("api/cashiers", CashierController::class, "list")
    ->middleware(IsAdminMiddleware::class)
    ->get();
