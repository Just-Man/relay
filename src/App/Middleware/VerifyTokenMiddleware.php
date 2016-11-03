<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 10.08.16
 * Time: 11:40
 */

namespace App\Middleware;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class VerifyTokenMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $signer = new Sha256();
        $key = getenv('PASS_KEY');
        $isVerify = false;
        $token = null;
        $tokenArray = [];

        if (isset($_GET['token'])) {
            $tokenArray = json_decode($request->getQueryParams()['token']);

        }

        if (isset($_POST['token'])) {
            $tokenArray = json_decode($request->getParsedBody()['token']);
            $tokenArray = json_decode($tokenArray);
        }
        
        $token = (new Parser())->parse((string) $tokenArray[0][1]);
        $isVerify = $token->verify($signer, $key);

        if ($isVerify) {
            $data = [
                "token" => $token
            ];
            $response = $response->withStatus(200,$data);
            return $next($request, $response);
        } else {
            return new JsonResponse(
                [
                    'user' => '',
                    'data' => false,
                    'error' => 'Token is not verified'
                ]
            );
        }
    }
}
