<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class GenerateTokenAction extends BaseAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $signer = new Sha256();

        $userData = $response->getReasonPhrase()['userData'];
        $tokenLiveTime = isset($response->getReasonPhrase()['token']) ? $response->getReasonPhrase()['token'] : 600;
        $createProfile = isset($response->getReasonPhrase()['createProfile']) ? $response->getReasonPhrase()['createProfile'] : false;

        $token = (new Builder())
            ->setIssuer("Api")                               // Configures the issuer (iss claim)
            ->setAudience("Client Side")                             // Configures the audience (aud claim)
            ->setIssuedAt(time())                                           // Configures the time that the token was issue (iat claim)
            ->setExpiration(time() + $tokenLiveTime)                                  // Configures the expiration time of the token (nbf claim)
            ->set('user_id', $userData['user_id'])                              // Configures a new claim, called "uid"
            ->set('username', $userData['username'])                           // Configures a new claim, called "uname"
            ->set('admin', $userData['is_admin'])                           // Configures a new claim, called "uname"
            ->sign($signer, "4SbR32@y")                                     // creates a signature using key
            ->getToken();                                                   // Retrieves the generated token
        if ($createProfile) {
            $data = [
                    'token' => token_get_all($token),
                    'createProfile' => $createProfile,
                ];
            $response = $response->withStatus(200, $data);
            return $next($request, $response);
        }
        return new JsonResponse([
            'token' => token_get_all($token),
            'user' => [
                "username" => $userData['username'],
                "is_admin" => $userData['is_admin'],
            ],
        ]);
    }
}
