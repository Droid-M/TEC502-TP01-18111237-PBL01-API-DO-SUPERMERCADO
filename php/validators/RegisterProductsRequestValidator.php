<?php

namespace php\validators;

use php\models\entities\Product;
use php\services\ProductService;
use php\services\Request;
use php\validators\RequestValidator;

class RegisterProductsRequestValidator extends RequestValidator
{
    public static function validate()
    {
        $products = Request::getInputParameters('products');
        $requiredColumns = array_filter(Product::COLUMNS, fn ($v) => $v != 'id' && $v != 'created_at');
        $invalidProductsData = [];
        if (is_null($products))
            abort(422, 'Dados inválidos!', ['products' => 'Campo é necessário!']);
        if (!is_array($products) || empty($products))
            abort(422, 'Dados inválidos!', ['products' => 'Campo deve ser um array com dados de produtos!']);
        foreach ($products as $index => $product) {
            // if (empty($product))
            //     $invalidProductsData[(string) $index] =  "O campo deve ser um array com dados de um produto!";
            foreach ($requiredColumns as $column) {
                if (!array_key_exists($column, $product) || is_null($product[$column])) {
                    $invalidProductsData[(string) $index] = "O campo '$column' é necessário!";
                    break;
                } else if ($column == "bar_code" && !is_null(ProductService::getByBarCode($product["bar_code"])))
                    abort(
                        422,
                        'Dados inválidos!',
                        [(string) $index => sprintf("O código de barras '%s' já está registrado! Tente outro!", $product["bar_code"])]
                    );
            }
        }
        if ($invalidProductsData)
            abort(422, 'Dados inválidos!', $invalidProductsData);
    }
}
