<?php

namespace php\traits;

use ReflectionClass;
use ReflectionProperty;

trait Arrayable
{
    private function convertToArray($items)
    {
        $result = [];
        foreach ($items as $key => $item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                $result[$key] = $this->convertToArray($item->toArray());
            } elseif (is_array($item)) {
                $result[$key] = $this->convertToArray($item);
            } else {
                $result[$key] = $item;
            }
        }
        return $result;
    }

    public function toArray()
    {
        $properties = [];
        $reflectionClass = new ReflectionClass($this);
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $properties[$propertyName] = $this->$propertyName;
        }
        return $this->convertToArray($properties);
    }
}
