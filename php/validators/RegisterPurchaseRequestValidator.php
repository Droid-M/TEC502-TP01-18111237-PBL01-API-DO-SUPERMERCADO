<?php

namespace phps\validators;

use php\services\ProductService;
use php\services\Request;
use php\validators\RequestValidator;

class RegisterPurchaseRequestValidator extends RequestValidator
{
    public static function validate()
    {
        $unavailableProducts = [];
        foreach (Request::getInputParameters('products_bar_code') as $index => $barCode) {
            $product = ProductService::getByBarCode($barCode);
            if (null == $product)
                $unavailableProducts[(string) $index] = 'Produto não registrado!';
            else if ($product->stock_quantity <= 0)
                $unavailableProducts[(string) $index] = 'Quantidade em estoque é insuficiente!';
        }
        if (count($unavailableProducts) > 0)
            abort(422, 'Dados inválidos!', ['products_bar_code' => $unavailableProducts]);
    }
}
