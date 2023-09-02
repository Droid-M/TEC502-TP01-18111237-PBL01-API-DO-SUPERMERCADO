<?php

namespace php\models\entities;

use php\traits\AttributesFillables;

class Cashier
{
    use AttributesFillables;
    
    public int $id;
    public string $ip;
    public bool $is_blocked;

    public function __construct(int $id, string $ip, bool $is_blocked)
    {
        $this->id = $id;
        $this->ip = $ip;
        $this->is_blocked = $is_blocked;
    }
}
