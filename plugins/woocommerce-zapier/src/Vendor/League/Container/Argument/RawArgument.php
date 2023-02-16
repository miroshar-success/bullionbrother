<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\Argument;

class RawArgument implements \OM4\WooCommerceZapier\Vendor\League\Container\Argument\RawArgumentInterface
{
    /**
     * @var mixed
     */
    protected $value;
    /**
     * {@inheritdoc}
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}
