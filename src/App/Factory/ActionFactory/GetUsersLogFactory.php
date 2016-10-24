<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 01.10.16
 * Time: 12:02
 */

namespace App\Factory\ActionFactory;


use Interop\Container\ContainerInterface;

class GetUsersLogFactory
{
 public function __invoke(ContainerInterface $container)
    {
        return new \App\Action\GetUsersLogAction($container);
    }
}