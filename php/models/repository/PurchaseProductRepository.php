<?php

namespace php\models\repository;

use php\helpers\Collection;
use php\models\entities\Product;
use php\models\entities\Purchase;
use php\models\entities\PurchaseProduct;

class PurchaseProductRepository extends Repository
{
    protected string $tableName = 'purchase_product';
    protected string $modelClass = PurchaseProduct::class;

    public function getPurchasesWithProducts()
    {
        /** @var Collection<string, Purchase> $purchases */
        $purchases = new Collection();
        foreach ($this->getAllManyToManyById(
            'products',
            'purchase_product',
            'purchases.id = purchase_product.purchase_id',
            'products.id = purchase_product.product_id',
            1,
            Purchase::COLUMNS,
            Product::COLUMNS
        ) as $dbLine) {
            $purchase = Purchase::fromArray($this->removeColumnsPrefix('purchases', $dbLine));
            $purchase = $purchases[$purchase->id] ?? $purchase; 
            $product = Product::fromArray($this->removeColumnsPrefix('products', $dbLine));
            $purchase->products->put($product->id, $product);
            $purchases->put($purchase->id, $purchase);
        }
        return $purchases;
    }

    public function registerNew(int $originCashier, float $totalValue, array $productsId, string $status = 'started')
    {
        $hasSaved = $this->save([
            'origin_cashier' => $originCashier,
            'status' => $status,
            'total_value' => $totalValue
        ]);
        if ($hasSaved)
            return Purchase::fromArray($this->getById($this->db->lastInsertId()));
        return null;
    }
}
