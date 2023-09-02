<?php

use php\controllers\CashierController;
use php\middlewares\UnlockedCashierMiddleware;
use php\services\Route;

/* ----------------------------- Rotas do caixa ----------------------------- */
Route::register("api/cashiers/{id}", CashierController::class, "index")->get();
Route::register("api/cashiers/{id}", CashierController::class, "index")->post();
Route::register("api/cashiers/{id}/free", CashierController::class, "index")->post();
Route::register("api/cashiers/{id}/block", CashierController::class, "index")->post();


/* ------------------------- Rotas do adminsitrador ------------------------- */
Route::register("api/cashiers", CashierController::class, "info")->get();