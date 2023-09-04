<?php

namespace php\models\entities;

class Product extends Model
{
    public ?int $id;
    public ?string $name;
    public ?string $created_at;
    public ?int $stock_quantity;
    public ?string $bar_code;
    public ?float $price;

    const COLUMNS = [
        'id',
        'name',
        'created_at',
        'stock_quantity',
        'price',
        'bar_code'
    ];

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?string $created_at = null,
        ?int $stock_quantity = null,
        ?string $bar_code = null,
        ?float $price = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->stock_quantity = $stock_quantity;
        $this->bar_code = $bar_code;
        $this->price = $price;
    }
}
