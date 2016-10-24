<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 04.10.16
 * Time: 14:51
 */

namespace App\Action;


use Lcobucci\JWT\Parser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateMailContentAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $token = json_decode($request->getParsedBody()['token']);
        $token = json_decode($token);

        $token = (new Parser())->parse((string)$token[0][1]);

        $admin = $token->getClaims();
        $adminName = json_encode($admin['username']);
        $user = json_decode($request->getParsedBody()['user'], true);

        $userToken = isset($response->getReasonPhrase()['token']) ? $response->getReasonPhrase()['token'] : null;
        $createProfile = isset($response->getReasonPhrase()['createProfile']) ? $response->getReasonPhrase()['createProfile'] : false;

        if ($createProfile) {
            $mails[] = $this->createProfileTemplate($userToken, $adminName, $user['email']);
        }

        foreach ($mails as $mail) {
            $response = $response->withStatus(200, $mail);
            return $next ($request, $response);
        }

    }

    private function createProfileTemplate($userToken, $adminName, $receiver)
    {
        $prefix = "http://";
        $realIP = file_get_contents("http://ipecho.net/plain");
        $token = json_encode($userToken);
        $token = urlencode($token);

        $link = $prefix . $realIP . "/activate?" . $token;
        $subject = 'User Registration';
        $messageBodyHtml = file_get_contents('templates/RegistrationTemplate.html');
        $messageBodyHtml = sprintf($messageBodyHtml, $adminName, $link);
        $messageBody = sprintf("Hello, %s crete you account for him relay software. This: %s is link for activation. Link will be active 1 hour", $adminName, $link);

        $createProfile = [
            "contentHtml" => $messageBodyHtml,
            "content" => $messageBody,
            "subject" => $subject,
            "sender" => $adminName,
            "receiver" => $receiver,
        ];

        return $createProfile;
    }
}