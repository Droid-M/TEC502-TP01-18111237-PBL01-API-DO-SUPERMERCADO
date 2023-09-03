<?php

namespace php\models\repository;

use php\helpers\Collection;
use php\models\entities\Cashier;

class CashierRepository extends Repository
{
    protected string $tableName = 'cashiers';
    protected string $modelClass = Cashier::class;
}
