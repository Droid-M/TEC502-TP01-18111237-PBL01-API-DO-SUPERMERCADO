<?php

namespace php\services;

use php\helpers\Collection;
use php\models\repository\ProductRepository;
use php\models\repository\PurchaseProductRepository;
use php\models\repository\PurchaseRepository;

class PurchaseService
{
    public static function getById(string $id)
    {
        return (new PurchaseRepository())->getPurchaseById($id);
    }

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

    public static function payPurchase(string $id, string $paymentMtd, string|null $purchaserName = '', string|null $purchaserCpf = '')
    {
        $productRepository = new ProductRepository();
        $purchase = (new PurchaseRepository())->updatePurchase($id, [
            'payment_method' => $paymentMtd,
            'status' => 'paid',
            'purchaser_name' => $purchaserName,
            'purchaser_cpf' => $purchaserCpf
        ]);
        foreach ($purchase->products->all() as $product) {
            if ($product->stock_quantity <= 0) 
                abort(403, 'Um dos itens da compra está indisponível em estoque!');
            $productRepository->updateProduct($product->id, ['stock_quantity' => --$product->stock_quantity]);
        }
        return $purchase;
    }
    
    public static function cancelPurchase(string $id)
    {
        return (new PurchaseRepository())->updatePurchase($id, [
            'status' => 'canceled',
        ]);
    }
}
