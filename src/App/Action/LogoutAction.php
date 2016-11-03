<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 21.10.16
 * Time: 12:53
 */

namespace App\Action;


use App\Constructor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class LogoutAction extends Constructor
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $cache = $this->container->get('Memcache');

        $token = $request->getParsedBody()['token'];

        $success = $cache->set("token", $token, false, 600);

        // $cacheContent = $cache->getAllKeys;

        // file_put_contents("/home/just/Documents/IT/relay/data/cacheContent.txt", json_encode($cacheContent));

        if ($success) {
            return new JsonResponse(
            [
                'data' => "You success logout",
                'error' => false
            ]
        );
        }
        return new JsonResponse(
            [
                'data' => false,
                'error' => 'Can\'t save to Cache'
            ]
        );
    }
}