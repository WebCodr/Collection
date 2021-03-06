<?php

namespace Collection\Habit;

trait AttributeTrait
{
    /**
     * Stores attributes
     * @var array
     */

    protected $attributes = [];

    /**
     * Constructor
     *
     * Accepts any arguments and saves them as attributes
     *
     * @param mixed [...]
     */

    public function __construct()
    {
        $args = func_get_args();

        if (!empty($args)) {
            if (is_array($args[0])) {
                $this->assign($args[0]);
            } else {
                $this->attributes = $args;
            }
        }
    }

    /**
     * Returns value of given attribute name or throws an exception if the attribute does not exist
     *
     * @param $attribute
     * @param bool $arrayAsMap
     * @return \Collection\MutableMap
     * @throws \OutOfBoundsException
     */

    public function get($attribute, $arrayAsMap = true)
    {
        if ($this->has($attribute)) {
            $value = $this->attributes[$attribute];

            if ($arrayAsMap === true && is_array($value)) {
                $value = new self($value);
            }

            return $value;
        }

        throw new \OutOfBoundsException("Attribute '{$attribute}' does not exist");
    }

    /**
     * Checks if given attribute is set
     *
     * @param $attribute
     * @return bool
     */

    public function has($attribute)
    {
        return isset($this->attributes[$attribute]);
    }

    /**
     * Returns the index of given value
     *
     * @param $value
     * @return mixed
     */

    public function index($value)
    {
        return array_search($value, $this->attributes, true);
    }

    /**
     * Sets a attribute and its value
     *
     * @param string $attribute
     * @param mixed $value
     * @return \Collection\MutableMap
     */

    public function set($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Removes an attribute
     *
     * @param $attribute
     * @return $this
     * @throws \OutOfBoundsException
     */

    public function remove($attribute)
    {
        if (!$this->has($attribute)) {
            throw new \OutOfBoundsException("Attribute '{$attribute}' does not exist");
        }

        unset($this->attributes[$attribute]);

        return $this;
    }

    /**
     * Removes an attribute by value
     *
     * @param $value
     * @return $this
     */

    public function delete($value)
    {
        $index = $this->index($value);

        if ($index !== false) {
            $this->remove($index);
        }

        return $this;
    }

    /**
     * Imports an array
     *
     * @param array $attributes
     * @return \Collection\MutableMap
     */

    public function assign(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Update attributes with from given array
     *
     * @param array $attributes
     * @return \Collection\MutableMap
     */

    public function update($attributes)
    {
        if (!empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $this->set($attribute, $value);
            }
        }

        return $this;
    }

    /**
     * Returns the keys of the attributes
     *
     * @return array
     */

    public function keys()
    {
        return array_keys($this->attributes);
    }

    /**
     * Returns all attributes as new MutableList
     *
     * @return array
     */

    public function all()
    {
        // get all attribute names
        $attributes = $this->keys();
        $list = new self();

        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                $list->set($attribute, $this->get($attribute));
            }
        }

        return $list;
    }

    /**
     * Returns all attributes as new MutableList
     *
     * @param bool $asAssoc
     * @return array
     */

    public function getArray($asAssoc = true)
    {
        // get all attribute names
        $attributes = $this->keys();
        $values = [];

        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                $value = $this->get($attribute, false);

                if ($asAssoc === true) {
                    $values[$attribute] = $value;
                } else {
                    $values[] = $value;
                }
            }
        }

        return $values;
    }
}