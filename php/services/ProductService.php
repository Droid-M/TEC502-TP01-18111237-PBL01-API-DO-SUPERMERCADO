<?php

namespace php\services;

use php\models\repository\ProductRepository;

class ProductService
{
    public static function getByBarCode(string $barCode)
    {
        return (new ProductRepository())->getByBarCode('bar_code', $barCode);
    }
}
