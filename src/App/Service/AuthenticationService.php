<?php
namespace App\Service;

use Zend\Diactoros\Response\JsonResponse;

class AuthenticationService extends BaseService
{
    public function authenticate($payload)
    {
        $result = false;

        if (! empty($payload['username']) && ! empty($payload['password'])) {

            $user_logRepo = $this->container->get('UserLogRepository');

            $userRepo = $this->container->get('UserRepository');
            $userData = $userRepo->getUser($payload['username']);
            if (!empty($userData) && password_verify($payload['password'], $userData['password'])) {
                $result = $userData;

                $user_logRepo->insert($userData['user_id']);
            }
        } else {
            return new JsonResponse([
                'data' => false,
                'error' => 'missing username or password'
            ]);
        }

        return $result;
    }
}