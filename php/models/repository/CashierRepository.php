<?php

namespace php\models\repository;

use PDO;
use php\helpers\Collection;
use php\models\entities\Cashier;
use php\models\entities\Product;
use php\models\entities\Purchase;

class CashierRepository extends Repository
{
    protected string $tableName = 'cashiers';
    protected string $modelClass = Cashier::class;

    private function fetchCashiersInfo(string $conditions = '', array $conditionValues = [])
    {
        $parentColumns = $this->prefixColumns($this->tableName, Cashier::COLUMNS);
        $relatedColumns1 = $this->prefixColumns('purchases', Purchase::COLUMNS);
        $relatedColumns2 = $this->prefixColumns('products', Product::COLUMNS);
        // $joinTableColumns = $this->prefixColumns('purchase_product', ['purchase_id', 'product_id', 'id']);
        /** @var Collection<string, Cashier> */
        $cashiers = new Collection();
        $query = "SELECT $parentColumns, $relatedColumns1, $relatedColumns2 FROM $this->tableName 
        LEFT JOIN purchases ON purchases.origin_cashier = cashiers.id 
        LEFT JOIN purchase_product ON purchase_product.purchase_id = purchases.id 
        LEFT JOIN products ON products.id = purchase_product.product_id $conditions";
        $stmt = $this->db->prepare($query);
        foreach ($conditionValues as $paramName => $paramValue) {
            $paramType = is_int($paramValue) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($paramName, $paramValue, $paramType);
        }
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $dbLine) {
            $cashierData = $this->removeColumnsPrefix($this->tableName, $dbLine);
            $cashier = $cashiers[$cashierData["id"]] ?? Cashier::fromArray($cashierData);
            $productData = $this->removeColumnsPrefix("products", $dbLine);
            $purchaseData = $this->removeColumnsPrefix('purchases', $dbLine);
            $purchase = $cashier->registered_purchases[$purchaseData['id']] ?? Purchase::fromArray($purchaseData);
            $product = $purchase->products[$productData['id']] ?? Product::fromArray($productData);
            $purchase->products->put($product->id, $product);
            $cashier->registered_purchases->put($purchase->id, $purchase);
            $cashiers->put($cashier->id, $cashier);
        }
        // return $this->choseManyReturn($cashiers)
        return $cashiers;
    }

    public function getCashierInfoByColumn(string $columnsName, string $columnValue)
    {
        return $this->fetchCashiersInfo("WHERE cashiers.$columnsName = :$columnsName", [":$columnsName" => $columnValue])->first();
    }

    public function listCashiersInfo()
    {
        return $this->fetchCashiersInfo();
    }
}
