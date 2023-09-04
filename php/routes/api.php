<?php

use php\controllers\CashierController;
use php\controllers\ProductController;
use php\controllers\PurchaseController;
use php\middlewares\IsAdminMiddleware;
use php\middlewares\IsUnregisteredCashierMiddleware;
use php\middlewares\IsCashierMiddleware;
use php\middlewares\IsUnlockedCashierMiddleware;
use php\services\Route;

/* ----------------------------- Cashier Routes ----------------------------- */

Route::register("api/cashiers/register", CashierController::class, "register")
    ->middleware(IsUnregisteredCashierMiddleware::class)
    ->post();
Route::register("api/cashiers/me/blocking-status", CashierController::class, "checkBlockStatus")
    ->middleware(IsCashierMiddleware::class)
    ->get();
Route::register("api/purchases/register", PurchaseController::class, "register")
    ->middleware(IsCashierMiddleware::class, IsUnlockedCashierMiddleware::class)
    ->post();
Route::register("api/purchases/{id}/pay", PurchaseController::class, "pay")
    ->middleware(IsCashierMiddleware::class)
    ->post();
Route::register("api/purchases/{id}/cancel", PurchaseController::class, "cancel")
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
Route::register('api/products/new', ProductController::class, 'registerProducts')
    ->middleware(IsAdminMiddleware::class)
    ->post();
Route::register('api/products/{id}/edit', ProductController::class, 'edit')
    ->middleware(IsAdminMiddleware::class)
    ->put();
Route::register("api/purchases/history", PurchaseController::class, "history")
    ->middleware(IsAdminMiddleware::class)
    ->get();
