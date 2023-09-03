<?php

namespace php\services;

use php\models\entities\Cashier;
use php\models\repository\CashierRepository;
use php\models\repository\PurchaseRepository;

class CashierService
{
    public static function list()
    {
        $cashierRepository = new CashierRepository();
        $purchaseRepository = new PurchaseRepository();
        $cashiers = $cashierRepository->returnAsArray()->getAllById();
        // foreach($cashiers as $cashier) {
        //     $cashier[]
        // }
        dd($purchaseRepository->getAllManyToMany(
            'products', 'purchase_product',
            'purchases.id = purchase_product.purchase_id',
            'products.id = purchase_product.product_id',
        )); 

    }
}
