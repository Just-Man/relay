<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            //Service
            App\Service\AuthenticationService::class => App\Factory\ServiceFactory\AbstractServiceFactory::class,

            //Middleware
            App\Middleware\VerifyTokenMiddleware::class => App\Factory\MiddlewareFactory\VerifyTokenFactory::class,
            App\Middleware\ValidateTokenMiddleware::class => App\Factory\MiddlewareFactory\ValidateTokenFactory::class,
            App\Middleware\ValidateMailMiddleware::class => App\Factory\MiddlewareFactory\ValidateMailFactory::class,
            App\Middleware\CheckTokenCacheMiddleware::class => App\Factory\MiddlewareFactory\CheckTokenCacheFactory::class,
            App\Middleware\GetModelMiddleware::class => App\Factory\MiddlewareFactory\GetModelFactory::class,
            App\Middleware\InputValidationMiddleware::class => App\Factory\MiddlewareFactory\InputValidationMiddleware::class,

            //Actions
            App\Action\GenerateTokenAction::class => App\Factory\ActionFactory\GenerateTokenFactory::class,
            App\Action\GetUsersAction::class => App\Factory\ActionFactory\GetUsersFactory::class,
            App\Action\GetUsersLogAction::class => App\Factory\ActionFactory\GetUsersLogFactory::class,
            App\Action\SaveUserAction::class => App\Factory\ActionFactory\SaveUserFactory::class,
            App\Action\LoginAction::class => App\Factory\ActionFactory\LoginFactory::class,
            App\Action\CreateUserAction::class => App\Factory\ActionFactory\CreateUserFactory::class,
            App\Action\GenerateTokenAction::class => App\Factory\ActionFactory\GenerateTokenFactory::class,
            App\Action\GetRelayStatusAction::class => App\Factory\ActionFactory\GetRelayStatusFactory::class,
            App\Action\SetRelaysAction::class => App\Factory\ActionFactory\SetRelaysFactory::class,
            App\Action\CreateMailContentAction::class => App\Factory\ActionFactory\CreateMailContentFactory::class,
            App\Action\SendMailAction::class => App\Factory\ActionFactory\SendMailFactory::class,
            App\Action\DeleteUserAction::class => App\Factory\ActionFactory\DeleteUserFactory::class,
            App\Action\LogoutAction::class => App\Factory\ActionFactory\LogoutFactory::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'login',
            'path' => '/login',
            'middleware' =>
                [
                    App\Action\LoginAction::class,
                    App\Action\GenerateTokenAction::class,
                ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'status_relay',
            'path' => '/status',
            'middleware' =>
                [
                    App\Middleware\CheckTokenCacheMiddleware::class,
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Action\GetRelayStatusAction::class
                ],
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'update_relay',
            'path' => '/set/[{id:\d+}]',
            'middleware' =>
                [
                    App\Middleware\CheckTokenCacheMiddleware::class,
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Middleware\GetModelMiddleware::class,
                    App\Middleware\InputValidationMiddleware::class,
                    App\Action\SetRelaysAction::class
                ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'get_users',
            'path' => '/users',
            'middleware' =>
                [
                    App\Middleware\CheckTokenCacheMiddleware::class,
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Action\GetUsersAction::class
                ],
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'log',
            'path' => '/log',
            'middleware' =>
                [
                    App\Middleware\CheckTokenCacheMiddleware::class,
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Action\GetUsersLogAction::class
                ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'create_user',
            'path' => '/create',
            'middleware' =>
                [
                    App\Middleware\CheckTokenCacheMiddleware::class,
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Middleware\ValidateMailMiddleware::class,
                    App\Middleware\GetModelMiddleware::class,
                    App\Middleware\InputValidationMiddleware::class,
                    App\Action\CreateUserAction::class,
                    App\Action\GenerateTokenAction::class,
                    App\Action\CreateMailContentAction::class,
                    App\Action\SendMailAction::class,
                    App\Action\GetUsersAction::class,
                ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'delete',
            'path' => '/del',
            'middleware' =>
                [
                    App\Middleware\CheckTokenCacheMiddleware::class,
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Action\DeleteUserAction::class
                ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'update_user',
            'path' => '/update',
            'middleware' =>
                [
                    App\Middleware\CheckTokenCacheMiddleware::class,
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Middleware\GetModelMiddleware::class,
                    App\Middleware\InputValidationMiddleware::class,
                    App\Action\SaveUserAction::class,

                ],
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'logout',
            'path' => '/logout',
            'middleware' =>
                [
                    App\Middleware\VerifyTokenMiddleware::class,
                    App\Middleware\ValidateTokenMiddleware::class,
                    App\Action\LogoutAction::class
                ],
            'allowed_methods' => ['POST'],
        ],
    ],
];
