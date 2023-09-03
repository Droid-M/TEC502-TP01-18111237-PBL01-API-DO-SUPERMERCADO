<?php

namespace php\models\repository;

use php\helpers\Collection;
use php\models\entities\Product;
use php\models\entities\Purchase;

class PurchaseRepository extends Repository
{
    protected string $tableName = 'purchases';
    protected string $modelClass = Purchase::class;

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
}
