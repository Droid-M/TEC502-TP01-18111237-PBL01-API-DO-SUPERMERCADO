<?php

namespace php\models\entities;


class Cashier extends Model
{
    public ?int $id;
    public ?string $ip;
    public ?bool $is_blocked;

    public function __construct(?int $id = null, ?string $ip = null, ?bool $is_blocked = null)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->is_blocked = $is_blocked;
    }
}