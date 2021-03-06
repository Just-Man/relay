<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 31.10.16
 * Time: 12:08
 */

namespace App\Factory\ModelFactory;

use Interop\Container\ContainerInterface;

class RelayModelFactory
{
public function __invoke(ContainerInterface $container)
    {
        return new \App\Model\RelayModel($container);
    }
}