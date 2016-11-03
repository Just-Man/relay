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

class SetRelaysAction extends BaseAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $isSaved = null;
        $relay = $request->getParsedBody()['relay'];
        $relay = json_decode($relay, true);

        $content = file_get_contents('data/relay.json');
        $content = substr($content, 0);
        $content = json_decode($content, true);

        $content[$relay["id"]] = $relay;
        
        $isSaved = file_put_contents('data/relay.json', json_encode($content));

        if ($isSaved) {
            return new JsonResponse(
                [
                    'data' => true,
                    'error' => false,
                ]
            );
        }
        
        return new JsonResponse(
            [
                'data' => false,
                'error' => 'Can\'t save data',
            ]
        );
    }
}
