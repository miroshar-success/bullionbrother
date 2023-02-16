<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider;

interface BootableServiceProviderInterface extends \OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider\ServiceProviderInterface
{
    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     */
    public function boot();
}
