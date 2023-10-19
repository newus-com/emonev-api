<?php

declare(strict_types=1);

namespace App\Application\Database;

use PDO;

class Database implements DatabaseInterface
{
    private $h;
    private $c;

    public function __construct(string $host, string $database, string $username, string $password)
    {
        $connection = new PDO("mysql:host={$host};dbname={$database}", $username, $password);

        $hydrahon = new \ClanCats\Hydrahon\Builder('mysql', function ($query, $queryString, $queryParameters) use ($connection) {
            $statement = $connection->prepare($queryString);
            $statement->execute($queryParameters);

            if ($query instanceof \ClanCats\Hydrahon\Query\Sql\FetchableInterface) {
                return $statement->fetchAll(\PDO::FETCH_ASSOC);
            }elseif ($query instanceof \ClanCats\Hydrahon\Query\Sql\Insert) {
                return $connection->lastInsertId();
            }else {
                return $statement->rowCount();
            }
        });

        $this->h = $hydrahon;
        $this->c = $connection;
    }

    /**
     * @return mixed
     */
    public function h()
    {
        return $this->h;
    }

    /**
     * @return mixed
     */
    public function c()
    {
        return $this->c;
    }
}
