<?php

namespace php\models\repository;

use php\helpers\Collection;
use php\models\entities\Product;
use php\models\entities\Purchase;

class PurchaseRepository extends Repository
{
    protected string $tableName = 'purchases';
    protected string $modelClass = Purchase::class;

    public function getPurchasesWithProducts(bool $orderAsc = true)
    {
        /** @var Collection<string, Purchase> $purchases */
        $purchases = new Collection();
        foreach ($this->getAllManyToManySortedWithCondition(
            'products',
            'purchase_product',
            'purchases.id = purchase_product.purchase_id',
            'products.id = purchase_product.product_id',
            '',
            [],
            Purchase::COLUMNS,
            Product::COLUMNS,
            'purchases.id',
            $orderAsc ? 'ASC' : 'DESC'
        ) as $dbLine) {
            $purchase = Purchase::fromArray($this->removeColumnsPrefix('purchases', $dbLine));
            $purchase = $purchases[$purchase->id] ?? $purchase;
            $productData = $this->removeColumnsPrefix("products", $dbLine);
            $productIdExists = array_key_exists('id', $productData);
            if (!$productIdExists || ($productIdExists && $productData['id'] != null)) {
                $product = $purchase->products[$productData['id']] ?? Product::fromArray($productData);
                $purchase->products->put($product->id, $product);
            }
            $purchase->products->put($product->id, $product);
            $purchases->put($purchase->id, $purchase);
        }
        return $purchases;
    }

    public function getPurchaseById(string $id)
    {
        $purchase = null;
        foreach ($this->getAllManyToManyById(
            'products',
            'purchase_product',
            'purchases.id = purchase_product.purchase_id',
            'products.id = purchase_product.product_id',
            $id,
            Purchase::COLUMNS,
            Product::COLUMNS
        ) as $dbLine) {
            if (is_null($purchase))
                $purchase = Purchase::fromArray($this->removeColumnsPrefix('purchases', $dbLine));
            $product = Product::fromArray($this->removeColumnsPrefix('products', $dbLine));
            $purchase->products->put($product->id, $product);
        }
        return $purchase;
    }

    public function registerNew(int $originCashier, float $totalValue, string $status = 'started')
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

    public function updatePurchase(int $id, array $data)
    {
        $data['id'] = $id;
        return $this->update($data, 'purchases.id = :id')
            ? $this->getPurchaseById($id)
            : null;
    }
}
