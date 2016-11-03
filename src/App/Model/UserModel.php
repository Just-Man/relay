<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 31.10.16
 * Time: 12:12
 */

namespace App\Model;


class UserModel
{
    public function getModel()
    {
        return [
            'user_id' => (int) 0,
            'username' => (string) "",
            'email' => "validateMail",
            'password' => "validatePassword",
            'is_admin' => (bool) false,
            ];
    }
}