<?php

/**
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */
namespace OM4\WooCommerceZapier\Vendor\Interop\Container\Exception;

use OM4\WooCommerceZapier\Vendor\Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;
/**
 * No entry was found in the container.
 */
interface NotFoundException extends \OM4\WooCommerceZapier\Vendor\Interop\Container\Exception\ContainerException, \OM4\WooCommerceZapier\Vendor\Psr\Container\NotFoundExceptionInterface
{
}
