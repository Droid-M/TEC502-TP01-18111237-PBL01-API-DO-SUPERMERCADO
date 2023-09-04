<?php

namespace php\services;

use php\models\repository\ProductRepository;

class ProductService
{
    public static function edit(int $productId, array $data)
    {
        return (new ProductRepository())->updateProduct($productId, $data)
    }

    public static function getByBarCode(string $barCode)
    {
        return (new ProductRepository())->getByBarCode('bar_code', $barCode);
    }

    public static function getAll()
    {
        return (new ProductRepository())->getAllProducts();
    }
}
