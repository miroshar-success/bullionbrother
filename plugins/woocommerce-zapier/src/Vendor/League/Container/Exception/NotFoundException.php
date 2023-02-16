<?php

namespace OM4\WooCommerceZapier\Vendor\League\Container\Exception;

use OM4\WooCommerceZapier\Vendor\Interop\Container\Exception\NotFoundException as NotFoundExceptionInterface;
use InvalidArgumentException;
class NotFoundException extends \InvalidArgumentException implements \OM4\WooCommerceZapier\Vendor\Interop\Container\Exception\NotFoundException
{
}
