<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 31.10.16
 * Time: 11:46
 */

namespace App\Middleware;


use App\Constructor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetModelMiddleware extends Constructor
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $routeResult = $request->getAttribute('Zend\Expressive\Router\RouteResult');
        $routeName = $routeResult->getMatchedRouteName();

        $modelName = preg_split("/_/", $routeName)[1];

        $modelName = ucfirst($modelName) . "Model";

        $model = $this->container->get($modelName)->getModel();

        $data = [
            "model" => $model
        ];

        $response = $response->withStatus(200, $data);
        return $next($request, $response);
    }
}