<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 21.10.16
 * Time: 12:10
 */

namespace App\Middleware;


use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CheckTokenCacheMiddleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $token = null;
        $memCache = $this->container->get('Memcache');

        $cachedToken = $memCache->get('token');

        if (isset($_GET['token'])) {
            $token = $request->getQueryParams()['token'];

        }

        if (isset($_POST['token'])) {
            $token = $request->getParsedBody()['token'];
        }
        if ($cachedToken == $token) {
            return new JsonResponse(
                [
                    'data' => false,
                    'error' => 'Token is blocked'
                ]
            );
        }
        $response = $response->withStatus(200, $token);
        return $next($request, $response);
    }

}