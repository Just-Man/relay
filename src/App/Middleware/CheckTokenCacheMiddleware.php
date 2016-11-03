<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 21.10.16
 * Time: 12:10
 */

namespace App\Middleware;


use App\Constructor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CheckTokenCacheMiddleware extends Constructor
{

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

        $cachedToken = substr($cachedToken, 0, strlen($cachedToken) - 1);

        if ($cachedToken == $token) {
            return new JsonResponse(
                [
                    'data' => false,
                    'error' => 'Token is already used and is blocked'
                ]
            );
        }
        $response = $response->withStatus(200, $token);
        return $next($request, $response);
    }

}