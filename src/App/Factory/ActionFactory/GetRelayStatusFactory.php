<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 13.08.16
 * Time: 13:40
 */

namespace App\Factory\ActionFactory;


use Interop\Container\ContainerInterface;

class GetRelayStatusFactory
{
public function __invoke(ContainerInterface $container)
    {
        return new \App\Action\GetRelayStatusAction($container);
    }
}