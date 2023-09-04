<?php

namespace php\services;

use php\helpers\Collection;
use php\models\repository\ProductRepository;
use php\models\repository\PurchaseRepository;

class PurchaseService
{
    public static function registerNewPurchase(int $originCashierId, array $productsBarCode)
    {
        $productRepository = new ProductRepository();
        $products = new Collection();
        $totalValue = 0;
        foreach ($productsBarCode as $barCode) {
            $product = $productRepository->getByBarCode($barCode);
            $products->put($product->id, $product);
            $totalValue += $product->price
        }

        $purchase = (new PurchaseRepository())->registerNew($originCashierId);
    }
}
