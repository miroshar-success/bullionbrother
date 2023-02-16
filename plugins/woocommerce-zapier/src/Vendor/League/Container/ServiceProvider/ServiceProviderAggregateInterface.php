<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider;

use OM4\WooCommerceZapier\Vendor\League\Container\ContainerAwareInterface;
interface ServiceProviderAggregateInterface extends \OM4\WooCommerceZapier\Vendor\League\Container\ContainerAwareInterface
{
    /**
     * Add a service provider to the aggregate.
     *
     * @param  string|\League\Container\ServiceProvider\ServiceProviderInterface $provider
     * @return $this
     */
    public function add($provider);
    /**
     * Determines whether a service is provided by the aggregate.
     *
     * @param  string $service
     * @return boolean
     */
    public function provides($service);
    /**
     * Invokes the register method of a provider that provides a specific service.
     *
     * @param  string $service
     * @return void
     */
    public function register($service);
}
