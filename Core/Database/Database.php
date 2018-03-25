<?php

namespace Jojotique\Framework\Database;

use PDO;

/**
 * Classes Database
 * @package Jojotique\Framework\Database
 */
class Database
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * Database constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
