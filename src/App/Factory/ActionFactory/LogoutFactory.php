<?php

namespace App\Factory\ActionFactory;

use Interop\Container\ContainerInterface;

class LogoutFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new \App\Action\LogoutAction($container);
    }
}
