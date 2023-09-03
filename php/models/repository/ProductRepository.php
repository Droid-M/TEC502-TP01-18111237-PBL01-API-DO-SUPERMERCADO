<?php

namespace php\models\repository;

use php\models\entities\Product;

class ProductRepository extends Repository
{
    protected string $tableName = 'products';
    protected string $modelClass = Product::class;
}
