<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 03.10.16
 * Time: 10:21
 */

namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ValidateMailMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $isValid = true;
        $error = [];
        $user = json_decode($request->getParsedBody()['user'], true);
        $email = $user['email'];

        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex + 1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                $error[] = "local part length exceeded";
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                $error[] = "domain part length exceeded";
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
                $error[] = "local part starts or ends with '.'";
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                $error[] = "local part has two consecutive dots";
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                $error[] = "character not valid in domain part";
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                $error[] = "domain part has two consecutive dots";
                $isValid = false;
            } else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                str_replace("\\\\", "", $local))
            ) {
                $error[] = "character not valid in local part unless local part is quoted";
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                    str_replace("\\\\", "", $local))
                ) {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                $error[] = "domain not found in DNS";
                $isValid = false;
            }
        }
        
        if (count($error) >= 1) {
            return new JsonResponse(
                [
                    'data' => false,
                    'error' => $error,
                ]
            );
        }
        
        return $next($request, $response);
    }
}