<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 04.10.16
 * Time: 14:52
 */

namespace App\Factory\ActionFactory;


use Interop\Container\ContainerInterface;

class CreateMailContentFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new \App\Action\CreateMailContentAction($container);
    }
}