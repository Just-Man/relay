<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 01.10.16
 * Time: 11:44
 */

namespace App\Action;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetUsersAction extends BaseAction
{
    protected $container;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $userRepo = $this->container->get('UserRepository');

        $user = json_decode($response->getReasonPhrase(), true);
        
        $this->isAdmin($user['user']);

        $users = $userRepo->getUser('', true);

        if ($users) {
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
                'error' => 'Can\'t get users list',
            ]
        );
    }

}