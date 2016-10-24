<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 01.10.16
 * Time: 11:44
 */

namespace App\Action;


use Lcobucci\JWT\Parser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetUsersLogAction extends BaseAction
{
    protected $container;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $userLogRepo = $this->container->get('UserLogRepository');

        $user = json_decode($request->getParsedBody()['user']);
 
        $users = $userLogRepo->getUserLog($user->user_id);

        if (empty($users)) {
            $users =
                [['user_id' => "$user->user_id", 'login_date' =>  "User don't have login yet"]];
        };

        return new JsonResponse(
            [
                'data' => $users,
                'error' => false,
            ]
        );
    }

}