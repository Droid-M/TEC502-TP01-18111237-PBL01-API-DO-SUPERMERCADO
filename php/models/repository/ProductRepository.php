<?php

namespace php\models\repository;

use php\models\entities\Product;

class ProductRepository extends Repository
{
    protected string $tableName = 'products';
    protected string $modelClass = Product::class;

    public function getByBarCode(string $barCode)
    {
        $dbLine = $this->getByColumn('bar_code', $barCode);
        if ($dbLine) 
            return Product::fromArray($dbLine);
        return null;
    }
}
