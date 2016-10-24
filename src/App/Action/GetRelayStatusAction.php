<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 13.08.16
 * Time: 13:01
 */

namespace App\Action;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Lcobucci\JWT\Parser;

class GetRelayStatusAction extends BaseAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $content = file_get_contents('data/relay.json');
        $content = substr($content, 0);
        $content = json_decode($content, true);
        
        if ($content) {
            return new JsonResponse(
                [
                    'data' => $content,
                    'error' => false,
                ]
            );
        }

        return new JsonResponse(
                [
                    'data' => false,
                    'error' => 'Can not read file content_' . $content,
                ]
            );
    }
}
