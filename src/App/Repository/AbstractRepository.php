<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 10.08.16
 * Time: 15:30
 */

namespace App\Repository;

use Aura\Sql\ExtendedPdo;

class AbstractRepository
{

    protected $connection;

    public function __construct()
    {
        $this->connection = new ExtendedPdo('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
    }
}