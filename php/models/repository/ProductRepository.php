<?php

namespace php\models\repository;

use php\helpers\Collection;
use php\models\entities\Product;

class ProductRepository extends Repository
{
    protected string $tableName = 'products';
    protected string $modelClass = Product::class;

    public function getByBarCode(string $barCode)
    {
        $dbLine = $this->getByColumn('bar_code', $barCode);
        if ($dbLine) 
            return Product::fromArray($dbLine);
        return null;
    }

    public function getAllProducts()
    {
        $products = new Collection();
        foreach ($this->getAll() as $dbLine) {
            $products->put($dbLine["id"], Product::fromArray($dbLine));
        }
        return $products;
    }

    public function updateProduct(int $id, array $data)
    {
        $data['id'] = $id;
        return $this->update($data, 'products.id = :id')
            ? Product::fromArray($this->getById($this->db->lastInsertId()))
            : null;
    }
        
}
