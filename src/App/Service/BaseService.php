<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 21.10.16
 * Time: 10:50
 */

namespace App\Service;


use Interop\Container\ContainerInterface;

class BaseService
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}