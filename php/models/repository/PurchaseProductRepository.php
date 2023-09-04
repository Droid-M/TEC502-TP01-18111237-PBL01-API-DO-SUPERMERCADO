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

    public function associateProductsToPurchase(string $purchaseId, array $productsId)
    {
        foreach ($productsId as $id) {
            $this->save(['product_id' => $id, 'purchase_id' => $purchaseId]);
        }
    }
}
