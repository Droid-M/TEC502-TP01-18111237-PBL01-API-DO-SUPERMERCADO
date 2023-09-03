<?php

namespace php\models\repository;

use php\models\entities\Product;

class PurchaseRepository extends Repository
{
    protected string $tableName = 'purchases';
    protected string $modelClass = Product::class;
}
