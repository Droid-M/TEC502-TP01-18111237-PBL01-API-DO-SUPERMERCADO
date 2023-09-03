<?php

namespace php\models\entities;

class Purchase extends Model
{
    public ?int $id;
    public ?string $created_at;
    public ?float $total_value;
    public ?bool $paid;
    public ?int $origin_cashier;
    public ?string $purchaser_name;
    public ?string $purchaser_cpf;
    public ?string $payment_method;

    public function __construct(
        ?int $id = null,
        ?string $created_at = null,
        ?float $total_value = null,
        ?bool $paid = null,
        ?int $origin_cashier = null,
        ?string $purchaser_name = null,
        ?string $purchaser_cpf = null,
        ?string $payment_method = null
    ) {
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
