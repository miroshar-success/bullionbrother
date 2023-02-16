<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container;

trait ContainerAwareTrait
{
    /**
     * @var \League\Container\ContainerInterface
     */
    protected $container;
    /**
     * Set a container.
     *
     * @param  \League\Container\ContainerInterface $container
     * @return $this
     */
    public function setContainer(\OM4\WooCommerceZapier\Vendor\League\Container\ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }
    /**
     * Get the container.
     *
     * @return \League\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
