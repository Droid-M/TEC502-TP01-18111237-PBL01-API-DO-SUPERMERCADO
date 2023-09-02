<?php

use php\controllers\CashierController;
use php\middlewares\UnlockedCashierMiddleware;
use php\services\Route;

/* ----------------------------- Rotas do caixa ----------------------------- */
Route::register("api/cashies", CashierController::class, "index")->get();
Route::register("api/cashies/{id}", CashierController::class, "index")->get();
Route::register("api/cashies/{id}", CashierController::class, "index")->post();
Route::register("api/cashies/{id}/free", CashierController::class, "index")->post();
Route::register("api/cashies/{id}/block", CashierController::class, "index")->post();