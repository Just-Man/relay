<?php

namespace App\Factory\ServiceFactory;

use Interop\Container\ContainerInterface;
use ReflectionClass;
use Zend\ServiceManager\Factory\FactoryInterface;

class AbstractServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $requestedName = 'App\Service\\' . $requestedName;
        // Construct a new ReflectionClass object for the requested action
        $reflection = new ReflectionClass($requestedName);
        // Get the constructor
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            // There is no constructor, just return a new class
            return new $requestedName;
        }

        // Get the parameters
        $parameters = $constructor->getParameters();
        $dependencies = [$container];
        foreach ($parameters as $parameter) {
            // Get the parameter class
            $class = $parameter->getClass();
            if ($class->getName() == 'Interop\Container\ContainerInterface') {
                $dependencies[] = $container;
            } else {
                // Get the class from the container
                $dependencies[] = $container->get($class->getName());
            }
        }

        // Return the requested class and inject its dependencies
        return $reflection->newInstanceArgs($dependencies);
    }

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        // Only accept Action classes
        if (substr($requestedName, -7) == 'Action') {
            return true;
        }

        return false;
    }
}
