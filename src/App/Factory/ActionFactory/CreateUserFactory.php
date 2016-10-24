<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 04.10.16
 * Time: 11:14
 */

namespace App\Factory\ActionFactory;


use Interop\Container\ContainerInterface;

class CreateUserFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new \App\Action\CreateUserAction($container);
    }

}