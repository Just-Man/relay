<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 02.10.16
 * Time: 13:08
 */

namespace App\Action;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class DeleteUserAction extends BaseAction
{

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $user = json_decode($response->getReasonPhrase(), true);
        $userRepo = $this->container->get('UserRepository');
        $userForDelete = json_decode($request->getParsedBody()['user'], true);

        $this->isAdmin($user);      

        $user = $userRepo->deleteUser($userForDelete['user_id']);

        $users = $userRepo->getUser('', true);

        if ($user) {
            return new JsonResponse(
                [
                    'data' => $users,
                    'error' => false,
                ]
            );
        }

        return new JsonResponse(
            [
                'data' => false,
                'error' => 'Can\'t delete user',
            ]
        );
    }
}