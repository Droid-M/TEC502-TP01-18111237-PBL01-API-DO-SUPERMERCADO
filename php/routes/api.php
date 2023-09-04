<?php

use php\controllers\CashierController;
use php\controllers\ProductController;
use php\controllers\PurchaseController;
use php\middlewares\IsAdminMiddleware;
use php\middlewares\IsUnregisteredCashierMiddleware;
use php\middlewares\IsCashierMiddleware;
use php\services\Route;

/* ----------------------------- Cashier Routes ----------------------------- */

Route::register("api/cashiers/register", CashierController::class, "register")
    ->middleware(IsUnregisteredCashierMiddleware::class)
    ->post();
Route::register("api/cashiers/me/blocking-status", CashierController::class, "checkBlockStatus")
    ->middleware(IsCashierMiddleware::class)
    ->get();
Route::register("api/purchases/register", PurchaseController::class, "register")
    ->middleware(IsCashierMiddleware::class)
    ->post();


/* ------------------------- Admin Routes ------------------------- */

Route::register("api/cashiers/{id}/manage", CashierController::class, "manage")
    ->middleware(IsAdminMiddleware::class)
    ->put();
Route::register("api/cashiers", CashierController::class, "list")
    ->middleware(IsAdminMiddleware::class)
    ->get();
Route::register('api/products', ProductController::class, 'list')
    ->middleware(IsAdminMiddleware::class)
    ->get();
Route::register('api/products/{id}/edit', ProductController::class, 'edit')
    ->middleware(IsAdminMiddleware::class)
    ->put();