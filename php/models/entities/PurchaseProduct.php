<?php

namespace php\models\entities;

class PurchaseProduct extends Model
{
    public ?int $id;
    public ?int $product_id;
    public ?int $purchase_id;

    public function __construct(?int $id = null, ?int $product_id = null, ?int $purchase_id = null)
    {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->purchase_id = $purchase_id;
    }
}
