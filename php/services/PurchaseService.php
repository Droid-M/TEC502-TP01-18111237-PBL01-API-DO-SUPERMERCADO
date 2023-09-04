<?php

namespace php\services;

use php\helpers\Collection;
use php\models\repository\ProductRepository;
use php\models\repository\PurchaseProductRepository;
use php\models\repository\PurchaseRepository;

class PurchaseService
{
    public static function registerNewPurchase(int $originCashierId, array $productsBarCode)
    {
        $productRepository = new ProductRepository();
        $totalValue = 0;
        $products = new Collection();
        $productsId = [];
        $purchaseProductRepository = new PurchaseProductRepository();
        foreach ($productsBarCode as $barCode) {
            $product = $productRepository->getByBarCode($barCode);
            $productsId[] = $product->id;
            $products->put($product->id, $product);
            $totalValue += $product->price;
        }
        $purchase = (new PurchaseRepository())->registerNew($originCashierId, float_to_currency($totalValue));
        $purchaseProductRepository->associateProductsToPurchase($purchase->id, $productsId);
        $purchase->products = &$products;
        return $purchase;
    }
}
