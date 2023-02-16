<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\Definition;

use OM4\WooCommerceZapier\Vendor\League\Container\Argument\ArgumentResolverInterface;
use OM4\WooCommerceZapier\Vendor\League\Container\Argument\ArgumentResolverTrait;
use OM4\WooCommerceZapier\Vendor\League\Container\ImmutableContainerAwareTrait;
abstract class AbstractDefinition implements \OM4\WooCommerceZapier\Vendor\League\Container\Argument\ArgumentResolverInterface, \OM4\WooCommerceZapier\Vendor\League\Container\Definition\DefinitionInterface
{
    use ArgumentResolverTrait;
    use ImmutableContainerAwareTrait;
    /**
     * @var string
     */
    protected $alias;
    /**
     * @var mixed
     */
    protected $concrete;
    /**
     * @var array
     */
    protected $arguments = [];
    /**
     * Constructor.
     *
     * @param string $alias
     * @param mixed  $concrete
     */
    public function __construct($alias, $concrete)
    {
        $this->alias = $alias;
        $this->concrete = $concrete;
    }
    /**
     * {@inheritdoc}
     */
    public function withArgument($arg)
    {
        $this->arguments[] = $arg;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function withArguments(array $args)
    {
        foreach ($args as $arg) {
            $this->withArgument($arg);
        }
        return $this;
    }
}
