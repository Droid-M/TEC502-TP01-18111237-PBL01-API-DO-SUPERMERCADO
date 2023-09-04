<?php

namespace php\controllers;

use Exception;
use php\services\Database;
use php\services\ProductService;
use php\services\Request;
use php\validators\RegisterPurchaseRequestValidator;

class ProductController
{
    public function list()
    {
        return json(
            200,
            'Produtos registrados com sucesso!',
            ['products' => ProductService::getAll()->toArray()]
        );
    }

    public function edit()
    {
        return Database::transaction(function () {
            return json(
                200, 
                'Produto atualizado com sucesso!',
                ProductService::edit(Request::getPathParameters('id'), Request::getInputParameters())->toArray(0)
            );
        });
    }
}
