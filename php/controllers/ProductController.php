<?php

namespace php\controllers;

use Exception;
use php\services\Database;
use php\services\ProductService;
use php\services\Request;
use php\validators\RegisterProductsRequestValidator;
use php\validators\RegisterPurchaseRequestValidator;
use TypeError;

class ProductController
{
    public function list()
    {
        return json(
            200,
            'Produtos consultados com sucesso!',
            ProductService::getAll()->toArray()
        );
    }

    public function edit()
    {
        try {
            return Database::transaction(function () {
                return json(
                    200, 
                    'Produto atualizado com sucesso!',
                    ProductService::edit(Request::getPathParameters('id'), Request::getInputParameters())->toArray(0)
                );
            });
        } catch (Exception|TypeError $e) {
            // return json(500, $e->getMessage());
            return json(500, 'Falha ao atualizar dados do produto');
        }
    }
    
    public function registerProducts()
    {
        RegisterProductsRequestValidator::validate();
        try {
            return Database::transaction(function () {
                return json(
                    200, 
                    'Registro de produto(s) efetuado com sucesso!',
                    ProductService::registerProducts(Request::getInputParameters('products'))->toArray(0)
                );
            });
        } catch (Exception|TypeError $e) {
            // return json(500, $e->getMessage());
            return json(500, 'Falha ao registrar produtos');
        }
    }
}