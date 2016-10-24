<?php

namespace App\Factory\ActionFactory;

use Interop\Container\ContainerInterface;

class LoginFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new \App\Action\LoginAction($container);
    }
}
