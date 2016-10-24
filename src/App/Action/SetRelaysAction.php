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
        $newContent = [];
        $relay = $request->getParsedBody()['relay'];
        $relay = json_decode($relay);

        $content = file_get_contents('data/relay.json');
        $content = substr($content, 0);
        $content = json_decode($content, true);

        foreach ($content as $item) {

            if ($item['id'] == $relay->id) {
                $item = $relay;
            }
            $newContent[] = $item;
        }

        $isSaved = file_put_contents('data/relay.json', json_encode($newContent));

        if ($isSaved) {
            return new JsonResponse(
                [
                    'data' => $newContent,
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
