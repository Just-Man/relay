<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 01.08.16
 * Time: 11:00
 */

namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Lcobucci\JWT\ValidationData;

class ValidateTokenMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {

        $data = new ValidationData();
        $isValid = false;

        $token = $response->getReasonPhrase()['token'];

        $isValid = $token->validate($data);

        $admin = $token->getClaims();
        $adminName = json_encode($admin['username']);
        $is_admin = json_encode($admin['admin']);
        $user = [
            "user" => [
                        'username' => $adminName,
                        'is_admin' => $is_admin,
                    ]
        ];
        
        if ($isValid) {
            $response = $response->withStatus(200, json_encode($user));
            return $next($request, $response);
        }

        return new JsonResponse(
            [
                'data' => false,
                'error' => 'Token is not valid',
            ]
        );

    }
}