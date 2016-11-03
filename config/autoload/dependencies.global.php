<?php
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            Application::class => ApplicationFactory::class,
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
            
            //Models
            RelayModel::class => App\Factory\ModelFactory\RelayModelFactory::class,
            UserModel::class => App\Factory\ModelFactory\UserModelFactory::class,

            //repositories
            AbstractRepository::class => App\Factory\RepositoryFactory\AbstractRepositoryFactory::class,
            UserRepository::class => App\Factory\RepositoryFactory\AbstractRepositoryFactory::class,
            UserLogRepository::class => App\Factory\RepositoryFactory\AbstractRepositoryFactory::class,


            //services
            AuthenticationService::class => App\Factory\ServiceFactory\AbstractServiceFactory::class,
            Memcache::class => App\Factory\ServiceFactory\MemCacheServiceFactory::class,
        ],
        'abstract_factories' => [
        ]
    ],
];
