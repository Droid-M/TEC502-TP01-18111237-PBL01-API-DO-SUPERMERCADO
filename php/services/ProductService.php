<?php

namespace php\services;

use php\helpers\Collection;
use php\models\repository\ProductRepository;

class ProductService
{
    public static function edit(int $productId, array $data)
    {
        return (new ProductRepository())->updateProduct($productId, $data);
    }

    public static function getByBarCode(string $barCode)
    {
        return (new ProductRepository())->getByBarCode($barCode);
    }

    public static function getAll()
    {
        return (new ProductRepository())->getAllProducts();
    }

    public static function registerProducts(array $productsData)
    {
        $productRepository = new ProductRepository();
        $products = new Collection();
        foreach ($productsData as $product) {
            $productInstance = $productRepository->addProduct(
                $product['name'],
                $product['stock_quantity'],
                $product['price'],
                $product['bar_code']
            );
            if (is_null($productInstance))
                abort(500, 'Falha ao registrar produto!', ['product_data' => $product]);
            $products->put($productInstance->id, $productInstance);
        }
        return $products;
    }
}
