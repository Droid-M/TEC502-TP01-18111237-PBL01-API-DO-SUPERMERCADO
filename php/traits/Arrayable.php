<?php

namespace php\traits;

use ReflectionClass;
use ReflectionProperty;

trait Arrayable
{
    public function toArray()
    {
        $properties = [];
        $reflectionClass = new ReflectionClass($this);
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $properties[$propertyName] = $this->$propertyName;
        }
        return $properties;
    }
}
