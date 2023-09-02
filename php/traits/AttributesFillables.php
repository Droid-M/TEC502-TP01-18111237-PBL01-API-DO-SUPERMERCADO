<?php

namespace php\traits;

trait AttributesFillables
{
    public function fillFromArray(array $data)
    {
        $classAttributes = get_object_vars($this);

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $classAttributes)) {
                $this->$key = $value;
            }
        }
    }
}
