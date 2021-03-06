<?php

namespace Collection\Habit;

trait ArrayTrait
{
    /**
     * Sets a property and its value
     *
     * @param string $property
     * @param mixed $value
     */

    public function offsetSet($property, $value)
    {
        $this->set($property, $value);
    }

    /**
     * Checks if given property name exists
     *
     * @param string $property
     * @return bool
     */

    public function offsetExists($property)
    {
        return isset($this->attributes[$property]);
    }

    /**
     * Removes a property
     *
     * @param string $property
     */

    public function offsetUnset($property)
    {
        $this->remove($property);
    }

    /**
     * Returns value of given property name
     *
     * @param string $property
     * @return mixed
     */

    public function offsetGet($property)
    {
        return $this->get($property);
    }
}