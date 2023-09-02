<?php

namespace php\models\entities;

use php\traits\AttributesFillables;

class Purchase
{
    use AttributesFillables;
    
    public int $id;
    public string $created_at;
    public float $total_value;
    public bool $paid;
    public int $origin_cashier;
    public ?string $purchaser_name;
    public ?string $purchaser_cpf;
    public string $payment_method;

    public function __construct(int $id, string $created_at, float $total_value, bool $paid, int $origin_cashier, ?string $purchaser_name, ?string $purchaser_cpf, string $payment_method)
    {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->total_value = $total_value;
        $this->paid = $paid;
        $this->origin_cashier = $origin_cashier;
        $this->purchaser_name = $purchaser_name;
        $this->purchaser_cpf = $purchaser_cpf;
        $this->payment_method = $payment_method;
    }
}
