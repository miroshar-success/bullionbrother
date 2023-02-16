<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container;

use OM4\WooCommerceZapier\Vendor\Interop\Container\ContainerInterface as InteropContainerInterface;
interface ImmutableContainerAwareInterface
{
    /**
     * Set a container
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(\OM4\WooCommerceZapier\Vendor\Interop\Container\ContainerInterface $container);
    /**
     * Get the container
     *
     * @return \League\Container\ImmutableContainerInterface
     */
    public function getContainer();
}
