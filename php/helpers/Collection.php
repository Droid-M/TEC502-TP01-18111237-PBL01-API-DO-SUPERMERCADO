<?php

namespace php\helpers;

use ArrayAccess;

class Collection implements ArrayAccess
{
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function first()
    {
        return array_first_value($this->items);
    }

    public function all()
    {
        return $this->items;
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->items[$key] : $default;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    public function put($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function forget($key)
    {
        if ($this->has($key)) {
            unset($this->items[$key]);
        }
    }

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
        $itemsCopy = $this->items;
        return $this->convertToArray($itemsCopy);
    }

    public function count()
    {
        return count($this->items);
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->put($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }
}
