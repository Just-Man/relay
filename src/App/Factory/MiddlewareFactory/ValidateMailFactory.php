<?php
namespace App\Factory\MiddlewareFactory;
use Interop\Container\ContainerInterface;

/**
 * Created by PhpStorm.
 * User: just
 * Date: 10.08.16
 * Time: 12:29
 */
class ValidateMailFactory
{
    public function __invoke(ContainerInterface $container)
        {
            return new \App\Middleware\ValidateMailMiddleware($container);
        }
}