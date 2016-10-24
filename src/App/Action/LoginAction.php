<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 01.08.16
 * Time: 10:57
 */

namespace App\Action;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class LoginAction extends BaseAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $jsonData = $request->getParsedBody();
        $jsonData = array_keys($jsonData);
        $payload = json_decode($jsonData[0], true);

        $authenticationService = $this->container->get('AuthenticationService');
        $userData = $authenticationService->authenticate($payload);

        if (is_object($userData)) {
            return $userData;
        }
        
        if ($userData) {
            $data = [
                "userData" => $userData
            ];
            $response = $response->withStatus(200,$data);
            return $next ($request, $response);
        }
        
        return new JsonResponse(
            [
                'data' => false,
                'error' => 'Username or password do not match'
            ]
        );
    }

}