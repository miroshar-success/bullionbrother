<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider;

use OM4\WooCommerceZapier\Vendor\League\Container\ContainerAwareInterface;
interface ServiceProviderInterface extends \OM4\WooCommerceZapier\Vendor\League\Container\ContainerAwareInterface
{
    /**
     * Returns a boolean if checking whether this provider provides a specific
     * service or returns an array of provided services if no argument passed.
     *
     * @param  string $service
     * @return boolean|array
     */
    public function provides($service = null);
    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register();
}
