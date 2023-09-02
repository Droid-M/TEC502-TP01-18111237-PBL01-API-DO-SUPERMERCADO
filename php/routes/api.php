<?php

use php\controllers\CashierController;
use php\middlewares\IsAdminMiddleware;
use php\middlewares\UnlockedCashierMiddleware;
use php\services\Route;

/* ----------------------------- Cashier Routes ----------------------------- */

Route::register("api/cashiers/{id}", CashierController::class, "index")->get();
Route::register("api/cashiers/{id}", CashierController::class, "index")->post();
Route::register("api/cashiers/{id}/free", CashierController::class, "index")->post();
Route::register("api/cashiers/{id}/block", CashierController::class, "index")->post();


/* ------------------------- Admin Routes ------------------------- */

Route::register("api/cashiers", CashierController::class, "list")->middleware(IsAdminMiddleware::class)->get();
