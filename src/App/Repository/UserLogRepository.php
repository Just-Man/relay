<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 01.10.16
 * Time: 15:09
 */

namespace App\Repository;


class UserLogRepository extends AbstractRepository
{
    function insert ($id) {
        $statement = "
        INSERT INTO
            users_log
            VALUE (?, FROM_UNIXTIME(?))";

        $bindParams = array($id,time());
        $query = $this->connection->prepare($statement);

        return $query->execute($bindParams);
    }
    
    function getUserLog ($id, $limit = 20) {
        $statement ="
        SELECT 
          *
        FROM
          users_log
        WHERE 
          user_id = ?
        ORDER BY login_date DESC 
        LIMIT ?";

        $bindParams = array($id, $limit);

        return $this->connection->fetchAll($statement, $bindParams);
    }
}