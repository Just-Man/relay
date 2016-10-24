<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 21.10.16
 * Time: 09:41
 */

namespace App\Factory\ServiceFactory;


use Interop\Container\ContainerInterface;

class MemCacheServiceFactory
{
    public static $memCache;
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $memCache = new \Memcache();
        $memCache->connect('localhost', 11211);
        
        return $memCache;
    }
}