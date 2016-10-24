<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 10.08.16
 * Time: 15:31
 */

namespace App\Repository;


class UserRepository extends AbstractRepository
{

    function getUser($username = '', $all = false)
    {
        if (!empty($username) && !$all) {
            $statement = "SELECT
                                user_id, username, password, is_admin
                          FROM
                                users
                          WHERE
                                username = :username ";
            $bindParams = array(
                'username' => $username,
            );

            return $this->connection->fetchOne($statement, $bindParams);
        }
        
        if ($all) {
            $statement = "SELECT
                                user_id, username, email, is_admin
                          FROM
                            users";
            
            return $this->connection->fetchAll($statement);
        }
    }
    
    function insertUser ($user)
    {
        if (!empty($user)) {

            $statement = '
                INSERT INTO users (';
            $valueStr = ") VALUE (";

            $values = [];
            $len = count($user);
            $count = 0;
            foreach ($user as $key => $item) {
                if ($count == $len - 1){
                    $statement .= $key;
                    $valueStr .= "?)";
                } else {
                    $statement .= $key . ",";
                    $valueStr .= "?, ";
                }
                $values[] = $item;

                $count += 1;
            }
            $query = $statement . $valueStr;

            $query = $this->connection->prepare($query);
         
            $query->execute($values);
            return $this->connection->lastInsertId();
        }
    }

    function updateUser ($user)
    {
        if (!empty($user)) {

            $statement = "UPDATE users SET ";

            $values = [];
            $len = count($user);
            $count = 0;
            foreach ($user as $key => $item) {
                if ($count == $len - 1) {
                    $statement .= $key . " = ? WHERE user_id = ?";
                } else {
                    $statement .= $key . " ?,";
                }
                $values[] = $item;

                $count += 1;
            }

            $values[] = $user['user_id'];

            $query = $this->connection->prepare($statement);

            return $query->execute($values);
        }
    }

    function deleteUser (int $userId)
    {
        $statement = "
                DELETE FROM 
                  users 
                WHERE 
                  user_id = ?";

        $bindParams[] = $userId;

        $query = $this->connection->prepare($statement);

        return $query->execute($bindParams);
    }
}