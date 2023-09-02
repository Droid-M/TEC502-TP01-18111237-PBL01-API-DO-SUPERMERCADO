<?php

use php\controllers\CashierController;
use php\middlewares\UnlockedCashierMiddleware;
use php\services\RouteService;

/* ----------------------------- Rotas do caixa ----------------------------- */
RouteService::register("api/cashies", CashierController::class, "index")->get();
RouteService::register("api/cashies/{id}", CashierController::class, "index")
    ->middleware(UnlockedCashierMiddleware::class)
    ->get();
RouteService::register("api/cashies/{id}", CashierController::class, "index")->post();
RouteService::register("api/cashies/{id}/free", CashierController::class, "index")->post();
RouteService::register("api/cashies/{id}/block", CashierController::class, "index")->post();