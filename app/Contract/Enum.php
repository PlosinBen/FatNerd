<?php

namespace App\Contract;

use Exception;

abstract class Enum
{
    protected $value;

    /**
     * @param $value
     * @throws Exception
     */
    public function __construct($value = null)
    {
        if ($value !== null && !in_array($value, $this->options())) {
            throw new Exception();
        }

        $this->value = $value;
    }

    /**
     * @return array
     */
    final public function all()
    {
        $instance = new static();

        return $instance->options();
    }

    /**
     * @param $method
     * @param $args
     * @return Enum
     * @throws Exception
     */
    public static function __callStatic($method, $args)
    {
        return new static($method);
    }

    abstract protected function options(): array;

    /**
     * @param $value
     * @return bool
     */
    public function is($value)
    {
        return $this->value === $value;
    }

    public function get()
    {
        return $this->value;
    }
}
