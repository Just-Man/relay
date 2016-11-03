<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 31.10.16
 * Time: 13:45
 */

namespace App\Factory\MiddlewareFactory;


use Interop\Container\ContainerInterface;

class InputValidationMiddleware
{
    public function __invoke(ContainerInterface $container)
    {
        return new \App\Middleware\InputValidationMiddleware($container);
    }
}