<?php

namespace App\Factory\ActionFactory;

use Interop\Container\ContainerInterface;

class GenerateTokenFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new \App\Action\GenerateTokenAction($container);
    }
}
