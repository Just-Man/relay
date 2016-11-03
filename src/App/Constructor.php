<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 31.10.16
 * Time: 12:32
 */

namespace App;


use Interop\Container\ContainerInterface;

class Constructor
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}