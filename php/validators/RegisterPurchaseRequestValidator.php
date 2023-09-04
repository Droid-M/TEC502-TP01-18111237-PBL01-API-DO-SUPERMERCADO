<?php

namespace php\validators;

use php\services\ProductService;
use php\services\Request;
use php\validators\RequestValidator;

class RegisterPurchaseRequestValidator extends RequestValidator
{
    public static function validate()
    {
        $unavailableProducts = [];
        if (is_null($productsBarCode = Request::getInputParameters('products_bar_code')))
            return abort(422, 'Dados inválidos!', ['products_bar_code' => 'Valor é necessário!']);
        if (!is_array($productsBarCode) || empty($productsBarCode))
            return abort(422, 'Dados inválidos!', ['products_bar_code' => 'Deve ser um array com códigos de barras']);
        foreach ($productsBarCode as $index => $barCode) {
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
