<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 09.08.16
 * Time: 14:49
 */

namespace App\Action;

use App\Constructor;
use Zend\Diactoros\Response\JsonResponse;


class BaseAction extends Constructor
{
    protected function generateRandomString($length = 6)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%^&"), 0, $length);
    }

    protected function isAdmin($user)
    {
        if (!$user['is_admin']) {
            return new JsonResponse(
                [
                    'data' => false,
                    'error' => 'You must be administrator',
                ]
            );
        }
    }
}