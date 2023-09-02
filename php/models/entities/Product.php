<?php

namespace php\models\entities;

use php\traits\AttributesFillables;

class Product
{
    use AttributesFillables;
    
    public int $id;
    public string $name;
    public string $created_at;
    public int $stock_quantity;
    public string $bar_code;

    public function __construct(int $id, string $name, string $created_at, int $stock_quantity, string $bar_code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->stock_quantity = $stock_quantity;
        $this->bar_code = $bar_code;
    }
}
