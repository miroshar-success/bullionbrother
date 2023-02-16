<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\Definition;

use OM4\WooCommerceZapier\Vendor\League\Container\ImmutableContainerAwareInterface;
interface DefinitionFactoryInterface extends \OM4\WooCommerceZapier\Vendor\League\Container\ImmutableContainerAwareInterface
{
    /**
     * Return a definition based on type of concrete.
     *
     * @param  string $alias
     * @param  mixed  $concrete
     * @return mixed
     */
    public function getDefinition($alias, $concrete);
}
