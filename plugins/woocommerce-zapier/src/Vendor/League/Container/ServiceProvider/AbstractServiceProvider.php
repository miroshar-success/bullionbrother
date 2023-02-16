<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider;

use OM4\WooCommerceZapier\Vendor\League\Container\ContainerAwareTrait;
abstract class AbstractServiceProvider implements \OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider\ServiceProviderInterface
{
    use ContainerAwareTrait;
    /**
     * @var array
     */
    protected $provides = [];
    /**
     * {@inheritdoc}
     */
    public function provides($alias = null)
    {
        if (!\is_null($alias)) {
            return \in_array($alias, $this->provides);
        }
        return $this->provides;
    }
}
