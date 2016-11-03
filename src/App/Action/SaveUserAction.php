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

class SaveUserAction extends BaseAction
{

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $user = $response->getReasonPhrase()["model"];

        $userRepo = $this->container->get('UserRepository');

        foreach ($user as $key => $item) {
            if  (empty($user[$key])) {
                unset($user[$key]);
            }
        }

        if  (isset($user['password'])) {
            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
        }

//"http://www.cafewebmaster.com/check-password-strength-safety-php-and-regex";
        $user = $userRepo->updateUser($user);
        $memCache = $this->container->get('Memcache');

        $is_cached = $memCache->set('token', $request->getParsedBody()['token'], false, 3600);

        if (!$is_cached) {
            return new JsonResponse(
                [
                    'data' => false,
                    'error' => 'Can\'t save token in to cache',
                ]
            );
        }

        if ($user) {
            return new JsonResponse(
                [
                    'data' => $user,
                    'error' => false,
                ]
            );
        }

        return new JsonResponse(
            [
                'data' => false,
                'error' => 'Can\'t save user',
            ]
        );
    }
}