<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\Definition;

use OM4\WooCommerceZapier\Vendor\League\Container\ImmutableContainerAwareTrait;
class DefinitionFactory implements \OM4\WooCommerceZapier\Vendor\League\Container\Definition\DefinitionFactoryInterface
{
    use ImmutableContainerAwareTrait;
    /**
     * {@inheritdoc}
     */
    public function getDefinition($alias, $concrete)
    {
        if (\is_callable($concrete)) {
            return (new \OM4\WooCommerceZapier\Vendor\League\Container\Definition\CallableDefinition($alias, $concrete))->setContainer($this->getContainer());
        }
        if (\is_string($concrete) && \class_exists($concrete)) {
            return (new \OM4\WooCommerceZapier\Vendor\League\Container\Definition\ClassDefinition($alias, $concrete))->setContainer($this->getContainer());
        }
        // if the item is not definable we just return the value to be stored
        // in the container as an arbitrary value/instance
        return $concrete;
    }
}
