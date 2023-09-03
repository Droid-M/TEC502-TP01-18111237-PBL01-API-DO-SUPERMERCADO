<?php

namespace php\models\entities;

use php\traits\Arrayable;
use php\traits\AttributesFillables;

abstract class Model
{
    use AttributesFillables, Arrayable;
}
