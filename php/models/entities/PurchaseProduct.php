<?php

namespace php\models\entities;

use php\traits\AttributesFillables;

class PurchaseProduct
{
    use AttributesFillables;
    
    public int $id;
    public int $product_id;
    public int $purchase_id;

    public function __construct(int $id, int $product_id, int $purchase_id)
    {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->purchase_id = $purchase_id;
    }
}
