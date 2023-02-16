<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider;

abstract class AbstractSignatureServiceProvider extends \OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider\AbstractServiceProvider implements \OM4\WooCommerceZapier\Vendor\League\Container\ServiceProvider\SignatureServiceProviderInterface
{
    /**
     * @var string
     */
    protected $signature;
    /**
     * {@inheritdoc}
     */
    public function withSignature($signature)
    {
        $this->signature = $signature;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getSignature()
    {
        return \is_null($this->signature) ? \get_class($this) : $this->signature;
    }
}
