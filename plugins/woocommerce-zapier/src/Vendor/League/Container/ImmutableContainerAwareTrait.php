<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container;

use OM4\WooCommerceZapier\Vendor\Interop\Container\ContainerInterface as InteropContainerInterface;
trait ImmutableContainerAwareTrait
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;
    /**
     * Set a container.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @return $this
     */
    public function setContainer(\OM4\WooCommerceZapier\Vendor\Interop\Container\ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }
    /**
     * Get the container.
     *
     * @return \League\Container\ImmutableContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
