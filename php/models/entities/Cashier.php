<?php

namespace php\models\entities;

use php\helpers\Collection;

class Cashier extends Model
{
    public ?int $id;
    public ?string $ip;
    public ?bool $is_blocked;
    /**
     * @var Collection<string, Purchase>
     */
    public Collection $registered_purchases;

    const COLUMNS = [
        'id', 
        'ip',
        'is_blocked'
    ];

    public function __construct(?int $id = null, ?string $ip = null, ?bool $is_blocked = null)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->is_blocked = $is_blocked;
        $this->registered_purchases = new Collection();
    }
}