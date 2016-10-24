<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 04.10.16
 * Time: 11:13
 */

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CreateUserAction extends BaseAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $user = json_decode($request->getParsedBody()['user'], true);
        
        $this->isAdmin($user);

        if ($user) {
            $userRepo = $this->container->get('UserRepository');

            $success = $userRepo->insertUser($user);

            if ($success) {
                $user['user_id'] = $success;
                $data = [
                    "userData" => $user,
                    "token" => 3600,
                    "createProfile" => true
                ];
                $response = $response->withStatus(200, $data);
                return $next($request, $response);
            }

            return new JsonResponse(
            [
                'data' => false,
                'error' => 'Can\'t save data',
            ]
        );
        }

        return new JsonResponse(
            [
                'data' => false,
                'error' => 'User object is empty',
            ]
        );
    }
}