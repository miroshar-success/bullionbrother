<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container;

interface ContainerAwareInterface
{
    /**
     * Set a container
     *
     * @param \League\Container\ContainerInterface $container
     */
    public function setContainer(\OM4\WooCommerceZapier\Vendor\League\Container\ContainerInterface $container);
    /**
     * Get the container
     *
     * @return \League\Container\ContainerInterface
     */
    public function getContainer();
}
